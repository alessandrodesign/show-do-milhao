<?php

namespace App\Http\Controllers;

use App\Http\Resources\GameScoreResource;
use App\Models\GameScore;
use Illuminate\Support\Facades\DB;

/**
 * Exibe ranking simples e detalhado.
 */
class RankingController extends Controller
{
    /**
     * Ranking simples (Top 20).
     */
    public function top()
    {
        $scores = GameScore::orderByDesc('prize')
            ->orderBy('created_at', 'asc')
            ->take(20)
            ->get();

        return GameScoreResource::collection($scores);
    }

    /**
     * Ranking detalhado com médias e percentuais.
     */
    public function detailed()
    {
        $stats = GameScore::select(
            'player_name',
            DB::raw('COUNT(*) as games'),
            DB::raw('AVG(prize) as avg_prize'),
            DB::raw('AVG(total_correct) as avg_correct'),
            DB::raw('SUM(prize) as total_prize')
        )
            ->groupBy('player_name')
            ->orderByDesc('avg_prize')
            ->take(20)
            ->get();

        return response()->json($stats);
    }
}
