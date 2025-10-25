<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class GameQuestionResource
 *
 * Estrutura JSON para perguntas com suas alternativas.
 */
class GameQuestionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'           => $this->id,
            'categoryId'   => $this->category_id,
            'difficultyId' => $this->difficulty_id,
            'question'     => $this->question,
            'hint'         => $this->hint,
            'answers'      => GameAnswerResource::collection($this->whenLoaded('answers')),
            'createdAt'    => $this->created_at?->toIso8601String(),
        ];
    }
}
