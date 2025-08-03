<?php

namespace App\Models\Repertoire;

use App\Models\Music\Music;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Repertoire extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo(User::class);
    }

   public function musics(){
        return $this->belongsToMany(Music::class, 'music_repertoire', 'repertoire_id', 'music_id')
                    ->withPivot('tom', 'position')
                    ->withTimestamps();
    }
}
