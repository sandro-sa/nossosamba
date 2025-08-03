<?php

namespace App\Models\Music;

use App\Models\Music\Tone;
use App\Models\Music\Rhythm;
use App\Models\Repertoire\Repertoire;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Music extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];

    protected $table = "musics";

    protected $cats = [
        'chords' => "array",
    ];

    public function tone(){
        return $this->belongsTo(Tone::class);
    }
    public function rhythm(){
        return $this->belongsTo(Rhythm::class);
    }
    public function singer(){
        return $this->belongsTo(Singer::class);
    }
    public function composers(){
        return $this->belongsToMany(Composer::class, 'composer_music', 'music_id', 'composer_id');
    }

    public function repertories(){
    return $this->belongsToMany(Repertoire::class, 'music_repertoire', 'music_id', 'repertoire_id')
                ->withPivot('tom', 'position')
                ->withTimestamps();
}
}
