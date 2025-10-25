<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class GameDifficultyResource
 *
 * Estrutura JSON para níveis de dificuldade.
 */
class GameDifficultyResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'prize' => (float) $this->prize,
            'order' => (int) $this->order,
        ];
    }
}
