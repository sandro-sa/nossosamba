<?php

namespace App\Http\Resources\Api\Repertoire;

use App\Models\Music\Music;
use Illuminate\Http\Request;
use App\Http\Resources\Api\Music\MusicResource;
use Illuminate\Http\Resources\Json\JsonResource;

class RepertoireResource extends JsonResource
{
    public function toArray(Request $request): array
{
    return [
        'id' => $this->id,
        'name' => $this->name,
        'musics' => $this->when(
            $this->musics && $this->musics->isNotEmpty(),
            fn () => $this->musics->map(function ($music) {
                return [
                    'id' => $music->id,
                    'music_name' => $music->music_name,
                    'tom' => $music->pivot->tom,
                    'position' => $music->pivot->position,
                ];
            })->sortBy('position')->values()
        ),
    ];
}

}