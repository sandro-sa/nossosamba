<?php

namespace App\Models\Music;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chord extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $cast = [
        'chord_positions' => 'array',
    ];
}
