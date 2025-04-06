<?php

namespace App\Models\Music;

use App\Models\Music\Music;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Composer extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
     
    protected $table = "composers";

    public function musics(){
        return $this->belongsToMany(Music::class);
    }
}
