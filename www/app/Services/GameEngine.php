<?php

namespace App\Services;

use App\Models\Answer;
use App\Models\Game;
use App\Models\GameQuestion;
use App\Models\Question;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class GameEngine
{
    /**
     * Premiação por etapa (1..16).
     * Mantenha os índices 1-based para combinar com a UI.
     */
    public const PRIZES = [
        1  => 1000,
        2  => 2000,
        3  => 3000,
        4  => 4000,
        5  => 5000,     // garantido 1
        6  => 10000,
        7  => 20000,
        8  => 30000,
        9  => 40000,
        10 => 50000,    // garantido 2
        11 => 100000,
        12 => 200000,
        13 => 300000,
        14 => 400000,
        15 => 500000,
        16 => 1000000,  // final (opcionalmente garantido)
    ];

    /**
     * Degraus de segurança (ao errar depois deles, mantém o valor).
     */
    public const SECURE_STEPS = [5, 10, 16];

    /**
     * Sorteia e cria 16 GameQuestions para um Game recém-criado.
     * Estratégia simples: 16 perguntas aleatórias, únicas.
     * (Se você tiver colunas de dificuldade, adapte a seleção aqui.)
     */
    public static function generateQuestionsForGame(Game $game): void
    {
        // Pega 16 perguntas aleatórias com pelo menos 1 resposta correta
        /** @var Collection<int, Question> $questions */
        $questions = Question::with('answers')
            ->inRandomOrder()
            ->get()
            ->filter(function (Question $q) {
                return $q->answers->where('is_correct', true)->count() === 1
                    && $q->answers->count() >= 4;
            })
            ->take(16)
            ->values();

        if ($questions->count() < 16) {
            throw new \RuntimeException('Não há perguntas suficientes com respostas válidas.');
        }

        DB::transaction(function () use ($game, $questions) {
            foreach ($questions as $idx => $q) {
                GameQuestion::create([
                    'game_id'     => $game->id,
                    'question_id' => $q->id,
                    'step'        => $idx + 1, // 1..16
                ]);
            }
        });
    }

    /**
     * Processa a resposta do usuário.
     * Atualiza prêmios, degraus e avanço de etapa.
     * Retorna payload para o front.
     */
    public static function processAnswer(Game $game, int $step, int $answerId): array
    {
        abort_if($game->finished, 422, 'Jogo já finalizado.');
        abort_if($step !== $game->current_step, 422, 'Etapa inválida.');

        /** @var GameQuestion|null $gq */
        $gq = GameQuestion::where('game_id', $game->id)
            ->where('step', $step)
            ->with('question.answers')
            ->first();

        abort_if(!$gq, 422, 'Pergunta não encontrada para esta etapa.');

        /** @var Answer|null $answer */
        $answer = $gq->question->answers->firstWhere('id', $answerId);
        abort_if(!$answer, 422, 'Resposta inválida para esta pergunta.');

        $isCorrect = (bool)$answer->is_correct;

        if ($isCorrect) {
            // Atualiza prêmio atual
            $currentPrize = self::PRIZES[$step] ?? 0;

            // Se atingiu um degrau de segurança, atualiza secured
            $securedPrize = $game->secured_prize;
            if (in_array($step, self::SECURE_STEPS, true)) {
                $securedPrize = $currentPrize;
            }

            $nextStep = $step + 1;
            $finished = $nextStep > 16;

            $game->update([
                'current_prize' => $currentPrize,
                'secured_prize' => $securedPrize,
                'current_step'  => $finished ? 16 : $nextStep,
                'finished'      => $finished,
            ]);

            return [
                'status'        => 'correct',
                'current_prize' => $currentPrize,
                'secured_prize' => $securedPrize,
                'next_step'     => $finished ? 16 : $nextStep,
                'finished'      => $finished,
            ];
        }

        // Resposta errada: finaliza e mantém o prêmio garantido
        $game->update([
            'finished'      => true,
            'current_prize' => $game->secured_prize, // cai para o garantido
        ]);

        return [
            'status'        => 'wrong',
            'current_prize' => $game->secured_prize,
            'secured_prize' => $game->secured_prize,
            'finished'      => true,
        ];
    }

    /**
     * Direciona chamada para a lifeline certa.
     */
    public static function processLifeline(Game $game, string $type, int $step): array
    {
        abort_if($game->finished, 422, 'Jogo já finalizado.');
        abort_if($step !== $game->current_step, 422, 'Etapa inválida.');

        return match ($type) {
            '5050'           => self::use5050($game, $step),
            'universitarios' => self::useUniversitarios($game, $step),
            'placas'         => self::usePlacas($game, $step),
            'pulo'           => self::usePulo($game, $step),
            default          => throw new InvalidArgumentException('Ajuda inválida.'),
        };
    }

    /**
     * 50/50 — mantém a correta e uma incorreta aleatória.
     * Retorna IDs a manter.
     */
    public static function use5050(Game $game, int $step): array
    {
        abort_if(!$game->lifeline_5050, 422, '50/50 já utilizada.');

        $gq = GameQuestion::where('game_id', $game->id)
            ->where('step', $step)
            ->with('question.answers')
            ->firstOrFail();

        $answers = $gq->question->answers;
        $correct = $answers->firstWhere('is_correct', true);
        $incorrects = $answers->where('is_correct', false)->values();

        // escolhe 1 incorreta aleatória para manter
        $keep = [$correct->id];
        $keep[] = $incorrects->random()->id;

        $game->update(['lifeline_5050' => false]);

        return ['keep' => $keep];
    }

    /**
     * Universitários — distribuição "inteligente":
     * correta com maior % (entre 45% e 70%),
     * restantes dividem o resto.
     */
    public static function useUniversitarios(Game $game, int $step): array
    {
        abort_if(!$game->lifeline_universitarios, 422, 'Universitários já utilizada.');

        $gq = GameQuestion::where('game_id', $game->id)
            ->where('step', $step)
            ->with('question.answers')
            ->firstOrFail();

        $answers = $gq->question->answers->values();
        $correctIndex = $answers->search(fn ($a) => (bool)$a->is_correct);

        // baseia pesos
        $correctPercent = random_int(45, 70);
        $remaining = 100 - $correctPercent;

        // gera 3 números que somem "remaining"
        $p1 = random_int(0, $remaining);
        $p2 = random_int(0, $remaining - $p1);
        $p3 = $remaining - $p1 - $p2;
        $dist = [$p1, $p2, $p3];
        shuffle($dist);

        $result = [];
        $idxDist = 0;
        foreach ($answers as $idx => $ans) {
            if ($idx === $correctIndex) {
                $result[] = ['answer_id' => $ans->id, 'percent' => $correctPercent];
            } else {
                $result[] = ['answer_id' => $ans->id, 'percent' => $dist[$idxDist++]];
            }
        }

        $game->update(['lifeline_universitarios' => false]);

        return $result;
    }

    /**
     * Placas — distribuição "mais caótica", mas ainda favorece a correta.
     */
    public static function usePlacas(Game $game, int $step): array
    {
        abort_if(!$game->lifeline_placas, 422, 'Placas já utilizada.');

        $gq = GameQuestion::where('game_id', $game->id)
            ->where('step', $step)
            ->with('question.answers')
            ->firstOrFail();

        $answers = $gq->question->answers->values();
        $correctIndex = $answers->search(fn ($a) => (bool)$a->is_correct);

        // correta entre 35..55, resto aleatório
        $correctPercent = random_int(35, 55);
        $remaining = 100 - $correctPercent;

        $p1 = random_int(0, $remaining);
        $p2 = random_int(0, $remaining - $p1);
        $p3 = $remaining - $p1 - $p2;
        $dist = [$p1, $p2, $p3];
        shuffle($dist);

        $result = [];
        $idxDist = 0;
        foreach ($answers as $idx => $ans) {
            if ($idx === $correctIndex) {
                $result[] = ['answer_id' => $ans->id, 'percent' => $correctPercent];
            } else {
                $result[] = ['answer_id' => $ans->id, 'percent' => $dist[$idxDist++]];
            }
        }

        $game->update(['lifeline_placas' => false]);

        return $result;
    }

    /**
     * Pulo — consome 1 e avança step (sem alterar prêmios).
     * Retorna o step atual após o pulo.
     */
    public static function usePulo(Game $game, int $step): array
    {
        abort_if($game->lifeline_pulo <= 0, 422, 'Sem pulos disponíveis.');
        abort_if($step !== $game->current_step, 422, 'Etapa inválida.');

        $next = min(16, $game->current_step + 1);

        $game->update([
            'lifeline_pulo' => $game->lifeline_pulo - 1,
            'current_step'  => $next,
        ]);

        return [
            'current_step'  => $game->current_step,
            'current_prize' => $game->current_prize,
            'secured_prize' => $game->secured_prize,
        ];
    }

    /**
     * Encerrar manualmente (Parar). Mantém prêmio garantido.
     */
    public static function quit(Game $game): void
    {
        if ($game->finished) {
            return;
        }

        $game->update([
            'finished'      => true,
            'current_prize' => max($game->current_prize, $game->secured_prize),
        ]);
    }
}
