<?php

namespace App\Http\Resources\Api\Music;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChordResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'chord_name' => $this->chord_name,
            'chord_positions' => $this->chord_positions,
        ];
    }
}