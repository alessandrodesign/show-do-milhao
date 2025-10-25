<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class GameAnswerRequest
 *
 * Validação individual das respostas de uma pergunta.
 */
class GameAnswerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'text'       => 'required|string|min:1|max:200',
            'is_correct' => 'required|boolean',
        ];
    }
}
