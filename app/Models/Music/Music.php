<?php

namespace App\Models\Music;

use App\Models\Music\Tone;
use App\Models\Music\Rhythm;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Music extends Model
{
    use HasFactory;

    protected $table = 'musics';

    protected $guarded = ['id'];

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
    
}
