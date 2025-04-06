<?php

namespace App\Http\Controllers\Web\App;

use App\Models\Music\Music;
use App\Http\Controllers\Controller;

class MusicUpdateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id)
    {
        
        try {
            $music = Music::where('id',(int)$id)->first();
            return view('music-app.music-update',['music' => $music]);
            
        } catch (\Exception $e) {
            return view('error.not-found');
        }
        
    }
}
