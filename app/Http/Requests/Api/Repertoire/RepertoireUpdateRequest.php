<?php

namespace App\Http\Requests\Api\Repertoire;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RepertoireUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
        'name' => [
            'required',
            'string',
            Rule::unique('repertoires')->where(function ($query) {
                return $query->where('user_id', $this->user()->id);
            }),
        ],
    ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Inserir um nome.',
            'name.unique' => 'Você já tem um repertório com esse nome.',
        ];
    }
}
