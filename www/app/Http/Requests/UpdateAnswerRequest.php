<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAnswerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('answer'));
    }

    public function rules(): array
    {
        return [
            'label' => ['required', 'string', 'max:1'],
            'text' => ['required', 'string'],
            'is_correct' => ['required', 'boolean'],
        ];
    }
}
