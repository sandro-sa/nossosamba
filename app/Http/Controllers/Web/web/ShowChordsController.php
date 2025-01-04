<?php

namespace  App\Http\Controllers\Web\Web;

use App\Models\Music\Chord;
use App\Http\Controllers\Controller;

class ShowChordsController extends Controller
{
    public function index()
    {
    
        $order = ['C', 'D', 'E', 'F', 'G', 'A', 'B'];
        $chords = Chord::orderByRaw("FIELD(chord_name, ?) ASC", [implode(',', $order)])->get();
        
        if(!$chords){
            return view('error.not-found');
        }
    
        return view('web.show-chord',[

            'chords' => $chords,
        ]);
      
    }
        
}
