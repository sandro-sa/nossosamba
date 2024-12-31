<?php

namespace App\Models\Music;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tone extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function musics(){
        return $this->hasMany(Music::class);
    }
}
