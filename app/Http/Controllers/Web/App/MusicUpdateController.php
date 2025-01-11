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
            $music = Music::findOrFail($id);
        } catch (\Exception $e) {
            return view('error.not-found');
        }
        
        return view('music-app.music-update',['music' => $music]);
    }
}
