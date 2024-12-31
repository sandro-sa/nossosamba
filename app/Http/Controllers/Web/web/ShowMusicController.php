<?php

namespace  App\Http\Controllers\Web\Web;

use App\Models\Music\Chord;
use App\Models\Music\Music;
use App\Http\Controllers\Controller;

class ShowMusicController extends Controller
{
    public function index($id)
    {
    
        $music = Music::where('id',$id)->first();
       
        if(!$music){
            return view('error.not-found');
        }

        $arrayChords = json_decode($music->chords);

        $chords = Chord::whereIn('chord_name', $arrayChords)->get();

        $music->load(['tone','singer','rhythm']);
        return view('web.show-music',[
            'music' => $music,
            'chords' => $chords,
        ]);
      
    }
        
}
