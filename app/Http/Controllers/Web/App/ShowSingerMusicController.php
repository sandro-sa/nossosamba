<?php

namespace  App\Http\Controllers\Web\App;

use App\Models\Music\Music;
use App\Http\Controllers\Controller;

class ShowSingerMusicController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(String $id)
    {
       
        return view('music-app.show-singer-music',[
           
        ]);
    }
}
