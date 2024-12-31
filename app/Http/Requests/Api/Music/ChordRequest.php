<?php

namespace App\Http\Requests\Api\Music;

use Illuminate\Foundation\Http\FormRequest;

class ChordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'chord_name' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Nome do acorde é obrigatório.',
        ];
    }
}
