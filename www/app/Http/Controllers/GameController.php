<?php

namespace App\Http\Controllers;

use App\Http\Resources\GameCategoryResource;
use App\Http\Resources\GameQuestionResource;
use App\Services\PrizeLadderService;
use App\Models\{GameCategory, GameSession, GameSessionQuestion, GameQuestion, GameAnswer, GameDifficulty, GameScore};
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Controlador principal do jogo Show do Milhão.
 * Controla fluxo, respostas, paradas e ajudas (lifelines).
 */
class GameController extends Controller
{
    /**
     * Inicia uma nova partida.
     */
    public function start(Request $request): JsonResponse
    {
        $data = $request->validate([
            'player_name' => 'required|string|min:3|max:100',
            'selected_categories' => 'required|array|min:1',
            'mode' => 'in:fixed,progressive',
            'fixed_difficulty_id' => 'nullable|exists:game_difficulties,id'
        ]);

        $mode = $data['mode'] ?? 'progressive';
        $categories = $data['selected_categories'];

        $lifelines = [
            'phone' => true,
            'audience' => true,
            'cards' => true,
            'skip' => 3, // pode pular até 3 vezes
        ];

        $session = GameSession::create([
            'player_name' => $data['player_name'],
            'selected_category_ids' => $categories,
            'mode' => $mode,
            'fixed_difficulty_id' => $data['fixed_difficulty_id'] ?? null,
            'lifelines_state' => $lifelines,
        ]);

        // Sorteia 15 perguntas
        $questions = GameQuestion::with('answers')
            ->whereIn('category_id', $categories)
            ->inRandomOrder()
            ->take(15)
            ->get();

        $round = 1;
        foreach ($questions as $question) {
            GameSessionQuestion::create([
                'session_id' => $session->id,
                'question_id' => $question->id,
                'round_number' => $round,
                'prize_value' => PrizeLadderService::prizeForRound($round),
            ]);
            $round++;
        }

        return response()->json([
            'session_id' => $session->id,
            'playerName' => $session->player_name,
            'mode' => $mode,
            'lifelines' => $lifelines,
            'currentQuestion' => 1,
        ]);
    }

    /**
     * Retorna estado atual da partida.
     */
    public function current(GameSession $game): JsonResponse
    {
        $question = $game->questions()
            ->with('question.answers')
            ->whereNull('is_correct')
            ->orderBy('round_number')
            ->first();

        if (!$question) {
            return response()->json(['message' => 'Jogo finalizado'], 400);
        }

        return response()->json([
            'game' => $game,
            'question' => new GameQuestionResource($question->question),
            'round' => $question->round_number,
            'currentPrize' => $game->current_prize,
            'lifelines' => $game->lifelines_state,
        ]);
    }

    /**
     * Registra resposta escolhida.
     */
    public function selectAnswer(Request $request, GameSession $game): JsonResponse
    {
        $data = $request->validate([
            'question_id' => 'required|exists:game_questions,id',
            'answer_id' => 'required|exists:game_answers,id',
        ]);

        $sessionQuestion = $game->questions()
            ->where('question_id', $data['question_id'])
            ->firstOrFail();

        $sessionQuestion->update(['selected_answer_id' => $data['answer_id']]);

        return response()->json(['message' => 'Resposta registrada']);
    }

    /**
     * Revela se a resposta está correta e atualiza prêmios.
     */
    public function reveal(GameSession $game): JsonResponse
    {
        $current = $game->questions()
            ->with(['question.answers'])
            ->whereNotNull('selected_answer_id')
            ->whereNull('is_correct')
            ->orderBy('round_number')
            ->firstOrFail();

        $selected = GameAnswer::find($current->selected_answer_id);
        $isCorrect = $selected?->is_correct ?? false;

        if ($isCorrect) {
            $newRound = $game->current_round + 1;
            $prize = PrizeLadderService::prizeForRound($newRound - 1);
            $game->current_prize = $prize;
            $game->safe_prize = PrizeLadderService::safePrizeForRound($newRound - 1);
            $game->current_round = $newRound;
            $current->update(['is_correct' => true]);
        } else {
            $game->final_prize = $game->safe_prize;
            $game->status = 'lost';
            $current->update(['is_correct' => false]);
        }

        $game->save();

        return response()->json([
            'correct' => $isCorrect,
            'newPrize' => $game->current_prize,
            'safePrize' => $game->safe_prize,
            'status' => $game->status,
        ]);
    }

    /**
     * Avança para próxima pergunta.
     */
    public function continue(GameSession $game): JsonResponse
    {
        if ($game->status !== 'running') {
            return response()->json(['message' => 'Jogo finalizado'], 400);
        }

        $next = $game->questions()
            ->whereNull('is_correct')
            ->orderBy('round_number')
            ->first();

        if (!$next) {
            $game->update(['status' => 'won', 'final_prize' => $game->current_prize]);
            GameScore::create([
                'player_name' => $game->player_name,
                'prize' => $game->current_prize,
                'total_correct' => 15,
                'total_wrong' => 0,
                'duration_seconds' => 0,
            ]);

            return response()->json(['message' => 'Certa resposta! Você ganhou R$ 1.000.000!', 'status' => 'won']);
        }

        return response()->json([
            'nextQuestion' => $next->round_number,
            'message' => 'Próxima pergunta disponível',
        ]);
    }

    /**
     * Jogador decide parar.
     */
    public function stop(GameSession $game): JsonResponse
    {
        $game->update([
            'status' => 'stopped',
            'final_prize' => $game->current_prize,
        ]);

        GameScore::create([
            'player_name' => $game->player_name,
            'prize' => $game->final_prize,
            'total_correct' => $game->questions()->where('is_correct', true)->count(),
            'total_wrong' => $game->questions()->where('is_correct', false)->count(),
            'duration_seconds' => 0,
        ]);

        return response()->json([
            'stopped' => true,
            'finalPrize' => $game->final_prize,
            'message' => 'Você decidiu parar e levar o prêmio!',
        ]);
    }

    /**
     * Usa uma ajuda (lifeline): phone, audience, cards, skip.
     */
    public function useLifeline(Request $request, GameSession $game, string $type): JsonResponse
    {
        $lifelines = $game->lifelines_state ?? [];
        $type = strtolower($type);

        if (!in_array($type, ['phone', 'audience', 'cards', 'skip'])) {
            return response()->json(['error' => 'Tipo de ajuda inválido'], 400);
        }

        // já usada?
        if (($type !== 'skip' && empty($lifelines[$type])) ||
            ($type === 'skip' && ($lifelines['skip'] ?? 0) <= 0)) {
            return response()->json(['error' => 'Ajuda já utilizada ou esgotada'], 400);
        }

        $response = null;
        switch ($type) {
            case 'phone': // Universitários
                $response = $this->simulatePhone();
                $lifelines['phone'] = false;
                break;

            case 'audience': // Plateia
                $response = $this->simulateAudience();
                $lifelines['audience'] = false;
                break;

            case 'cards': // Cartas
                $response = $this->simulateCards();
                $lifelines['cards'] = false;
                break;

            case 'skip': // Pular
                $lifelines['skip'] = max(0, ($lifelines['skip'] ?? 0) - 1);
                $response = $this->simulateSkip($game);
                break;
        }

        $game->update(['lifelines_state' => $lifelines]);

        return response()->json([
            'type' => $type,
            'result' => $response,
            'remaining' => $lifelines,
        ]);
    }

    /* ===========================================================
       SIMULADORES DE AJUDA
       =========================================================== */

    private function simulatePhone(): array
    {
        $confidence = rand(60, 95);
        $answer = ['A', 'B', 'C', 'D'][rand(0, 3)];
        return [
            'confidence' => $confidence,
            'answer' => $answer,
            'message' => 'Universitário acha que a resposta certa é ' . $answer,
        ];
    }

    private function simulateAudience(): array
    {
        $total = 100;
        $A = rand(10, 40);
        $B = rand(10, 40);
        $C = rand(10, 40);
        $D = max(0, $total - ($A + $B + $C));

        return [
            'A' => $A,
            'B' => $B,
            'C' => $C,
            'D' => $D,
            'message' => 'Platéia votou! Veja as porcentagens acima.',
        ];
    }

    private function simulateCards(): array
    {
        $correctCard = rand(0, 3);
        $cards = [0, 1, 2, 3];
        shuffle($cards);
        return [
            'selectedCards' => $cards,
            'hintRevealed' => [
                'cardIndex' => $correctCard,
                'isCorrect' => true,
            ],
            'message' => 'Uma carta indica a alternativa mais provável.',
        ];
    }

    private function simulateSkip(GameSession $game): array
    {
        $current = $game->questions()
            ->whereNull('is_correct')
            ->orderBy('round_number')
            ->first();

        if (!$current) {
            return ['skipped' => false, 'message' => 'Nenhuma pergunta para pular.'];
        }

        $categoryIds = $game->selected_category_ids ?? [];
        $newQuestion = GameQuestion::with('answers')
            ->whereIn('category_id', $categoryIds)
            ->where('id', '!=', $current->question_id)
            ->inRandomOrder()
            ->first();

        if ($newQuestion) {
            $current->update(['question_id' => $newQuestion->id]);
            return [
                'skipped' => true,
                'newQuestion' => new GameQuestionResource($newQuestion),
                'message' => 'Pergunta pulada! Nova pergunta carregada.',
            ];
        }

        return ['skipped' => false, 'message' => 'Não foi possível trocar a pergunta.'];
    }

    public function categories()
    {
        return GameCategoryResource::collection(GameCategory::all());
    }
}
