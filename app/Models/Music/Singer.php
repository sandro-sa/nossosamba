<?php

namespace App\Models\Music;

use App\Models\Music\Music;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Singer extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function musics(){
        return $this->hasMany(Music::class);
    }
}
