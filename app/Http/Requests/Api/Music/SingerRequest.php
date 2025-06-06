<?php

namespace App\Http\Requests\Api\Music;

use Illuminate\Foundation\Http\FormRequest;

class SingerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "singer_name" => ["required"],
            "singer_type" => ["required"],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
    public function messages(): array
    {
        return [
            "singer_name.required" => "Preencher todos o nome do músico ou banda.",
            "singer_type.required" => "Preencher tipo músico ou banda.",
        ];
    }
}
