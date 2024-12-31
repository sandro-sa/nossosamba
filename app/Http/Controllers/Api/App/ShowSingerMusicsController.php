<?php

namespace  App\Http\Controllers\Api\App;

use App\Exceptions\Music\MusicException;
use App\Models\Music\Chord;
use App\Models\Music\Music;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Music\ChordResource;
use App\Http\Resources\Api\Music\MusicResource;



class ShowSingerMusicsController extends Controller
{
    public function __invoke($id)
    {
        $musics = Music::where('singer_id', $id)->get();
        $chordNames = [];
        
        foreach ($musics as $music) {
            $arrayChords = json_decode($music->chords);
            
            if (is_array($arrayChords)) {
                foreach ($arrayChords as $chordName) {
                    if (!in_array($chordName, $chordNames)) {
                        $chordNames[] = $chordName;
                    }
                }
            }
        }
    
        $chords = Chord::whereIn('chord_name', $chordNames)->get();
        
        if ($chords->isEmpty()) {
            throw new MusicException('Não há musicas disponíveis');
        }


        $musics->load(['rhythm', 'tone']);
        
        // Aqui, você já tem a coleção pronta para ser retornada
        return response()->json([
            'musics' => MusicResource::collection($musics),
            'chords' => ChordResource::collection($chords),
        ]);
    }
    
    
}