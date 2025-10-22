<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request para criação de respostas de perguntas do Show do Milhão.
 * Garante que a resposta tenha rótulo único (A, B, C ou D) e texto obrigatório.
 */
class StoreAnswerRequest extends FormRequest
{
    /**
     * Verifica autorização.
     * Somente administradores podem cadastrar respostas.
     */
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    /**
     * Regras de validação para criação de respostas.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'label' => ['required', 'string', 'max:1'],
            'text' => ['required', 'string', 'max:500'],
            'is_correct' => ['required', 'boolean'],
        ];
    }

    /**
     * Mensagens personalizadas de erro.
     */
    public function messages(): array
    {
        return [
            'label.required' => 'O rótulo da alternativa é obrigatório (A, B, C ou D).',
            'text.required' => 'O texto da resposta é obrigatório.',
            'is_correct.required' => 'Informe se a resposta é a correta.',
        ];
    }
}
