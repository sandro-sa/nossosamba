<?php

namespace App\Http\Requests\Api\Music;

use Illuminate\Foundation\Http\FormRequest;

class MusicRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'singer_id' => 'required',
            'tone_id' => 'required',
            'rhythm_id' => 'required',
            'introduction' => 'nullable',
            'music_name' => 'required',
            'music' => 'required',
            'chords' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'music_name.required' => 'Nome da musica é obrigatório.',
            'music.required' => 'Musica é obrigatório.',
            'singer_id.required' => 'Musico é obrigatório.',
            'rhythm_id.required' => 'Ritmo é obrigatório.',
            'tone_id.required' => 'Tom é obrigatório.',
            'chords.required' => 'Notas não incluidas.',
        ];
    }
}
