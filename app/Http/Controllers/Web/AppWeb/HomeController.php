<?php

namespace App\Http\Controllers\Web\AppWeb;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    
    public function index()
    {
        return view('home');
    }
}
