<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class GameAnswerResource
 *
 * Estrutura JSON para alternativas das perguntas.
 */
class GameAnswerResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'        => $this->id,
            'text'      => $this->text,
            'isCorrect' => (bool) $this->is_correct,
        ];
    }
}
