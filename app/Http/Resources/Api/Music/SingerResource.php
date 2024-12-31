<?php

namespace App\Http\Resources\Api\Music;

use Illuminate\Http\Request;
use App\Http\Resources\Api\Music\SingerMusicsResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SingerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'singer_name' => $this->singer_name,
            'singer_type' => $this->singer_type,
            'musics' => $this->when($this->musics && $this->musics->isNotEmpty(), SingerMusicsResource::collection($this->musics))

        ];
    }
}