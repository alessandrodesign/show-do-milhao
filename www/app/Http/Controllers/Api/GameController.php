<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alternative;
use App\Models\Game;
use App\Models\GameScore;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Controller principal do ambiente de jogo Show do Milhão.
 *
 * Responsável por iniciar partidas, entregar perguntas,
 * validar respostas, gravar histórico, gerar ranking e
 * simular as ajudas (universitários).
 */
class GameController extends Controller
{
    use LogsActions;

    /**
     * Escada de prêmios padrão (10 perguntas)
     * @var int[]
     */
    private array $prizeLadder = [
        1_000, 2_000, 5_000, 10_000, 20_000,
        30_000, 50_000, 100_000, 200_000, 1_000_000,
    ];

    /**
     * Inicia uma nova partida.
     */
    public function start(Request $request)
    {
        $data = $request->validate([
            'player_name'    => 'required|string|max:100',
            'category_ids'   => 'nullable|array',
            'category_ids.*' => 'integer|exists:categories,id',
            'mode'           => 'required|string|in:fixed,progressive',
            'fixed_level'    => 'nullable|integer|min:1|max:5',
        ]);

        $game = Game::create([
            'player_name' => $data['player_name'],
            'score'       => 0,
        ]);

        $this->logAction('game_start', 'Game', $game->id, $data);

        return response()->json([
            'game_id'         => $game->id,
            'player_name'     => $data['player_name'],
            'category_ids'    => $data['category_ids'] ?? [],
            'mode'            => $data['mode'],
            'fixed_level'     => $data['fixed_level'] ?? null,
            'prizes'          => $this->prizeLadder,
            'total_questions' => count($this->prizeLadder),
        ]);
    }

    /**
     * Retorna uma pergunta de acordo com o progresso atual.
     */
    public function question(Request $request)
    {
        $data = $request->validate([
            'game_id'                => 'required|exists:games,id',
            'current_index'          => 'required|integer|min:0',
            'category_ids'           => 'nullable|array',
            'category_ids.*'         => 'integer|exists:categories,id',
            'mode'                   => 'required|string|in:fixed,progressive',
            'fixed_level'            => 'nullable|integer|min:1|max:5',
            'exclude_question_ids'   => 'nullable|array',
            'exclude_question_ids.*' => 'integer',
        ]);

        $level = $this->resolveLevelFromIndex(
            $data['mode'],
            $data['fixed_level'] ?? null,
            $data['current_index']
        );

        $query = Question::query()
            ->whereHas('difficulty', fn ($q) => $q->where('level', $level))
            ->with(['alternatives:id,question_id,letter,text'])
            ->select(['id', 'category_id', 'difficulty_id', 'statement']);

        if (!empty($data['category_ids'])) {
            $query->whereIn('category_id', $data['category_ids']);
        }

        if (!empty($data['exclude_question_ids'])) {
            $query->whereNotIn('id', $data['exclude_question_ids']);
        }

        $question = $query->inRandomOrder()->first();

        if (!$question) {
            return response()->json(['message' => 'Nenhuma pergunta disponível.'], 404);
        }

        return response()->json([
            'question' => [
                'id'           => $question->id,
                'statement'    => $question->statement,
                'alternatives' => $question->alternatives->shuffle()->values(),
            ],
            'level' => $level,
        ]);
    }

    /**
     * Verifica se a alternativa enviada é correta e grava resultado.
     */
    public function answer(Request $request)
    {
        $data = $request->validate([
            'game_id'         => 'required|exists:games,id',
            'question_id'     => 'required|exists:questions,id',
            'alternative_id'  => 'required|exists:alternatives,id',
            'index'           => 'required|integer|min:0',
            'response_ms'     => 'nullable|integer|min:0',
            'total_questions' => 'required|integer|min:1|max:100',
        ]);

        $isCorrect = Alternative::where('id', $data['alternative_id'])
            ->where('question_id', $data['question_id'])
            ->where('is_correct', true)
            ->exists();

        DB::transaction(function () use ($data, $isCorrect) {
            GameScore::create([
                'game_id'     => $data['game_id'],
                'question_id' => $data['question_id'],
                'correct'     => $isCorrect,
                'response_ms' => $data['response_ms'] ?? null,
            ]);

            if ($isCorrect) {
                $prize = $this->prizeLadder[$data['index']] ?? 0;
                Game::where('id', $data['game_id'])->increment('score', $prize);

                // vitória: se acertou a última questão, encerra o jogo
                if (($data['index'] + 1) >= $data['total_questions']) {
                    Game::where('id', $data['game_id'])->update(['ended_at' => now()]);
                }
            } else {
                // errou: encerra imediatamente
                Game::where('id', $data['game_id'])->update(['ended_at' => now()]);
            }

            $this->logAction('answer', 'GameScore', null, [
                'game_id'     => $data['game_id'],
                'question_id' => $data['question_id'],
                'correct'     => $isCorrect,
            ]);
        });

        return response()->json(['correct' => $isCorrect]);
    }

    /**
     * Finaliza manualmente a partida (por desistência ou vitória).
     */
    public function end(Request $request)
    {
        $data = $request->validate(['game_id' => 'required|exists:games,id']);
        $game = Game::find($data['game_id']);
        if (!$game) {
            return response()->json(['message' => 'Partida não encontrada.'], 404);
        }
        $game->ended_at = now();
        $game->save();

        return response()->json(['message' => 'Partida finalizada', 'score' => $game->score]);
    }

    /**
     * Ranking dos 20 melhores jogadores.
     */
    public function ranking()
    {
        $ranking = Game::whereNotNull('ended_at')
            ->orderByDesc('score')
            ->limit(20)
            ->get(['player_name', 'score', 'ended_at']);

        return response()->json($ranking);
    }

    /**
     * Simula a ajuda "Universitários" devolvendo votos aleatórios.
     */
    public function helpUniversitarios(Request $request)
    {
        $data = $request->validate(['question_id' => 'required|exists:questions,id']);
        $alts = Alternative::where('question_id', $data['question_id'])->get();

        $votes   = [];
        $correct = $alts->firstWhere('is_correct', true);
        foreach ($alts as $a) {
            $votes[$a->letter] = rand(5, 25);
        }
        if ($correct) {
            $votes[$correct->letter] += 30 + rand(10, 20);
        }

        return response()->json(['votes' => $votes]);
    }

    /**
     * Determina o nível de dificuldade com base no modo e índice.
     */
    private function resolveLevelFromIndex(string $mode, ?int $fixedLevel, int $index): int
    {
        if ($mode === 'fixed') {
            return max(1, min(5, (int) $fixedLevel));
        }

        // Progressive: 10 perguntas escalonadas do fácil ao extremo
        $map = [1, 1, 2, 2, 3, 3, 4, 4, 5, 5];

        return $map[$index] ?? 5;
    }

    public function stats(Request $request, int $gameId)
    {
        $game = Game::find($gameId);
        if (!$game) {
            return response()->json(['message' => 'Partida não encontrada.'], 404);
        }

        $rows    = GameScore::where('game_id', $gameId)->get(['correct', 'response_ms']);
        $total   = $rows->count();
        $correct = $rows->where('correct', true)->count();
        $wrong   = $total - $correct;

        $avgMs   = (int) round($rows->whereNotNull('response_ms')->avg('response_ms') ?? 0);
        $bestMs  = (int) ($rows->whereNotNull('response_ms')->min('response_ms') ?? 0);
        $worstMs = (int) ($rows->whereNotNull('response_ms')->max('response_ms') ?? 0);

        return response()->json([
            'game_id'                  => $gameId,
            'player_name'              => $game->player_name,
            'score'                    => $game->score,
            'ended_at'                 => $game->ended_at,
            'total_questions_answered' => $total,
            'correct'                  => $correct,
            'wrong'                    => $wrong,
            'accuracy'                 => $total ? round($correct * 100 / $total, 1) : 0,
            'avg_response_ms'          => $avgMs,
            'best_response_ms'         => $bestMs,
            'worst_response_ms'        => $worstMs,
        ]);
    }
}
