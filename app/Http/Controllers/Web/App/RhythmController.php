<?php

namespace  App\Http\Controllers\Web\App;

use App\Http\Controllers\Controller;

class RhythmController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('music-app.rhythm');
    }
}
