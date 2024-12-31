<?php

namespace App\Http\Requests\Api\Music;

use Illuminate\Foundation\Http\FormRequest;

class RhythmRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "rhythm" => ["required"],
            "time" => ["required"],
        ];
    }
    public function messages(): array
    {
        return [
            "rhythm.required" => "Preencher tipo de samba.",
            "time.required" => "Preencher tempo do ritímo.",
        ];
    }
}
