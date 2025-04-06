<?php

namespace App\Http\Resources\Api\Music;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComposerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'amount_musics' => $this->amount_musics,
            'music_names' => $this->musics->pluck('music_name')->unique(),
        ];
    }
}