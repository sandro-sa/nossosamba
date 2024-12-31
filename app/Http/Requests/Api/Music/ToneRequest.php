<?php

namespace App\Http\Requests\Api\Music;

use Illuminate\Foundation\Http\FormRequest;

class ToneRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'tone' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Tom do acorde é obrigatório.',
        ];
    }
}
