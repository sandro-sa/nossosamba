<?php

namespace App\Http\Resources\Api\Repertoire;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SongResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'music_id' => $this->music_id,
            'repertoire_id' => $this->repertoire_id,
            'position' => $this->position
        ];
    }
}