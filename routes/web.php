<?php

#use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Web\App\ToneController;
use App\Http\Controllers\Web\App\ChordController;
use App\Http\Controllers\Web\App\MusicController;
use App\Http\Controllers\Web\App\RhythmController;
use App\Http\Controllers\Web\App\SingerController;
use App\Http\Controllers\Web\AppWeb\HomeController;
use App\Http\Controllers\Web\App\CreateMusicController;
use App\Http\Controllers\Web\AppWeb\ShowMusicController;
use App\Http\Controllers\Web\AppWeb\ShowChordsController;
use App\Http\Controllers\Web\App\ShowSingerMusicController;
use App\Http\Controllers\Web\App\ShowSingerMusicsController;

//Auth::routes();

Route::get('login',[LoginController::class,'showLoginForm'])->name('showLoginForm');
Route::post('login',[LoginController::class,'login'])->name('login');
// Route::get('register',[RegisterController::class,'showRegistrationForm'])->name('showRegistrationForm');
// Route::post('register',[RegisterController::class,'register'])->name('register');



Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/letra/{id}', [ShowMusicController::class,'index']);
Route::get('/cifras', [ShowChordsController::class,'index'])->name('chord');

Route::middleware(['auth', 'web'])->group(function () {
    
    Route::get('/musica', [MusicController::class, 'index'])->name('musica');
    Route::post('logout',[LoginController::class,'logout'])->name('logout');
    Route::get('/acorde', [ChordController::class, 'index'])->name('acorde');
    Route::get('/musico', [SingerController::class, 'index'])->name('musico');
    Route::get('/editor', [CreateMusicController::class, 'index'])->name('editor');
    Route::get('/tom', [ToneController::class, 'index'])->name('tom');
    Route::get('/ritimo', [RhythmController::class, 'index'])->name('ritimo');
    Route::get('lista/musicas/{id}', [ShowSingerMusicsController::class, 'index'])->name('lista.musicas');
    Route::get('ver/musica/', [ShowSingerMusicController::class, 'index'])->name('ver.musica');

});