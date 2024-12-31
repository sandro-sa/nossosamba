<?php

namespace  App\Http\Controllers\Web\App;

use App\Models\Music\Music;
use App\Models\Music\Singer;
use App\Http\Controllers\Controller;

class ShowSingerMusicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(String $id)
    {
        $singer = Singer::findOrFail($id);
        
        return view('music-app.show-singer-musics',[
            'singer' => $singer
        ]);
    }
}
