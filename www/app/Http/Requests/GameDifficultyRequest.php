<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class GameDifficultyRequest
 *
 * Validações para níveis de dificuldade e prêmios.
 */
class GameDifficultyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'  => 'required|string|min:3|max:30',
            'prize' => 'required|numeric|min:1',
            'order' => 'required|integer|unique:game_difficulties,order,' . $this->id,
        ];
    }
}
