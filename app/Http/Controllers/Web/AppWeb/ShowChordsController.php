<?php

namespace App\Http\Controllers\Web\AppWeb;

use App\Models\Music\Chord;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShowChordsController extends Controller
{
    public function index(String $chord)
    {
        switch ($chord) {
            case 'C':
            $chord = "C";
            break;
            case 'D':
            $chord = "D";
            break;
            case 'E':
            $chord = "E";
            break;
            case 'F':
            $chord = "F";
            break;
            case 'G':
            $chord = "G";
            break;
            case 'A':
            $chord = "A";
            break;
            case 'B':
            $chord = "B";
            break;
            default:
            $chord = "C";
                break;
        }
        $chords = Chord::where('chord_name', 'like', $chord.'%')
                ->orderBy('chord_name', 'asc')
                ->get();
     
        if(!$chords){
            return view('error.not-found');
        }
    
        return view('web.show-chord',[

            'chords' => $chords,
        ]);
      
    }
        
}
