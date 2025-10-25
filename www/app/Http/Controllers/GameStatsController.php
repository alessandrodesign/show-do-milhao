<?php

namespace App\Http\Controllers;

use App\Models\GameScore;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

/**
 * Fornece estatísticas globais e por jogador.
 */
class GameStatsController extends Controller
{
    /**
     * Estatísticas globais do sistema.
     */
    public function global(): JsonResponse
    {
        $totalGames = GameScore::count();
        $totalPlayers = GameScore::distinct('player_name')->count('player_name');
        $avgPrize = round(GameScore::avg('prize'), 2);
        $avgCorrect = round(GameScore::avg('total_correct'), 1);
        $totalPrizePaid = GameScore::sum('prize');

        return response()->json([
            'total_games' => $totalGames,
            'total_players' => $totalPlayers,
            'avg_prize' => $avgPrize,
            'avg_correct_answers' => $avgCorrect,
            'total_prize_paid' => $totalPrizePaid,
        ]);
    }

    /**
     * Estatísticas de um jogador específico.
     */
    public function player(string $name): JsonResponse
    {
        $scores = GameScore::where('player_name', $name)->orderByDesc('created_at')->get();

        if ($scores->isEmpty()) {
            return response()->json(['message' => 'Nenhum jogo encontrado para este jogador.'], 404);
        }

        $avgPrize = round($scores->avg('prize'), 2);
        $avgAccuracy = round(($scores->avg('total_correct') / 15) * 100, 1);
        $totalGames = $scores->count();

        return response()->json([
            'player_name' => $name,
            'total_games' => $totalGames,
            'avg_prize' => $avgPrize,
            'avg_accuracy' => $avgAccuracy,
            'last_games' => $scores->take(10)->map(fn($s) => [
                'date' => $s->created_at->toDateTimeString(),
                'prize' => $s->prize,
                'correct' => $s->total_correct,
                'wrong' => $s->total_wrong,
            ]),
        ]);
    }
}
