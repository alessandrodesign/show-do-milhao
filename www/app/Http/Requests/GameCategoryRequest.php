<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class GameCategoryRequest
 *
 * Validações para criação e atualização de categorias do jogo.
 */
class GameCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|min:3|max:50',
            'description' => 'nullable|string|max:200',
        ];
    }
}
