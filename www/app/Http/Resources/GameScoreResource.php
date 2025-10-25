<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class GameScoreResource
 *
 * Estrutura JSON do ranking (pontuações).
 */
class GameScoreResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'              => $this->id,
            'playerName'      => $this->player_name,
            'prize'           => (float) $this->prize,
            'totalCorrect'    => (int) $this->total_correct,
            'totalWrong'      => (int) $this->total_wrong,
            'durationSeconds' => (int) $this->duration_seconds,
            'createdAt'       => $this->created_at?->toIso8601String(),
        ];
    }
}
