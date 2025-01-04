<?php

namespace App\Http\Controllers\Web\AppWeb;

use App\Models\Music\Chord;
use App\Http\Controllers\Controller;

class ShowChordsController extends Controller
{
    public function index()
    {
        $chords = Chord::orderBy('chord_name', 'asc')->get();
     
        if(!$chords){
            return view('error.not-found');
        }
    
        return view('web.show-chord',[

            'chords' => $chords,
        ]);
      
    }
        
}
