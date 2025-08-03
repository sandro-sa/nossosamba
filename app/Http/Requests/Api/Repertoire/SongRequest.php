<?php

namespace App\Http\Requests\Api\Repertoire;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SongRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            // 'repertoire_id' => ['required'],
            'music_id' => ['required'],
            'tom' => 'nullable'
        ];
    }

    public function messages(): array
    {
        return [
            'repertoire_id.required' => 'Inserir um repertorio.',
            'music_id.required' => 'Inserir uma musica.',
        ];
    }
}
