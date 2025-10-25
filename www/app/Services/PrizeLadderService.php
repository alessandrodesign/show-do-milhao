<?php

namespace App\Services;

use App\Models\GameDifficulty;

/**
 * Controla a escada de prêmios e checkpoints do Show do Milhão.
 */
class PrizeLadderService
{
    /**
     * Retorna a lista de prêmios ordenada pelo nível.
     *
     * @return array<int, array{name: string, prize: float, order: int, is_safe: bool}>
     */
    public static function getPrizeLadder(): array
    {
        $levels = GameDifficulty::orderBy('order')->get(['name', 'prize', 'order'])->toArray();

        // Marca checkpoints (perguntas 5 e 10)
        foreach ($levels as &$level) {
            $level['is_safe'] = in_array($level['order'], [5, 10]);
        }

        return $levels;
    }

    /**
     * Retorna o valor de prêmio da rodada.
     */
    public static function prizeForRound(int $round): float
    {
        $diff = GameDifficulty::where('order', $round)->first();
        return $diff?->prize ?? 0.0;
    }

    /**
     * Retorna o valor do último patamar seguro atingido.
     */
    public static function safePrizeForRound(int $round): float
    {
        if ($round >= 10) {
            return self::prizeForRound(10);
        }

        if ($round >= 5) {
            return self::prizeForRound(5);
        }

        return 0.0;
    }
}
