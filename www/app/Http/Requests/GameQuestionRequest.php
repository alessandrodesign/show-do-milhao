<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class GameQuestionRequest
 *
 * Validações completas para criação e atualização de perguntas.
 */
class GameQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id'          => 'required|exists:game_categories,id',
            'difficulty_id'        => 'required|exists:game_difficulties,id',
            'question'             => 'required|string|min:10|max:500',
            'hint'                 => 'nullable|string|max:300',
            'answers'              => 'required|array|size:4',
            'answers.*.text'       => 'required|string|min:1|max:200',
            'answers.*.is_correct' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'answers.size' => 'Cada pergunta deve conter exatamente 4 alternativas.',
        ];
    }
}
