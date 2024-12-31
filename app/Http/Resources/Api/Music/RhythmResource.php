<?php

namespace App\Http\Resources\Api\Music;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RhythmResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'rhythm' => $this->rhythm,
            'time' => $this->time,
            //'musics' => $this->when($this->musics, MusicResource::collection($this->musics))
        ];
    }
}