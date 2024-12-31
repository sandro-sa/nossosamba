<?php

namespace App\Http\Resources\Api\Music;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ToneResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tone' => $this->tone,
            //'musics' => $this->when($this->musics, MusicResource::collection($this->musics))
        ];
    }
}