<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlayerUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'nickname' => ['nullable', 'string', 'max:50'],
            'score' => ['nullable', 'integer', 'min:0'],
            'best_prize' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
