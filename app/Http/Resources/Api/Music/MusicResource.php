<?php

namespace App\Http\Resources\Api\Music;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MusicResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'introduction' => $this->introduction,
            'music_name' => $this->music_name,
            'music' => $this->music,
            "singer" => $this->when($this->singer, new SingerResource($this->singer)),
            "tone" => $this->when($this->tone, new ToneResource($this->tone)),
            "rhythm" => $this->when($this->rhythm, new RhythmResource($this->rhythm)),
        ];
    }
}