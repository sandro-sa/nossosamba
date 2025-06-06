<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\App\ToneController;
use App\Http\Controllers\Api\App\ChordController;
use App\Http\Controllers\Api\App\MusicController;
use App\Http\Controllers\Api\App\RhythmController;
use App\Http\Controllers\Api\App\SingerController;

use App\Http\Controllers\Api\Web\AllSingersController;
use App\Http\Controllers\Web\AppWeb\ShowChordsController;
use App\Http\Controllers\Api\App\ShowSingerMusicsController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('all_singer',[AllSingersController::class,'index']);
Route::get('all_chord',[ShowChordsController::class,'index']);


Route::middleware(['auth', 'web'])->group(function (){

    Route::apiResource('singer',SingerController::class);
    Route::get('singer_musics/{id}',ShowSingerMusicsController::class);
    Route::apiResource('tone',ToneController::class);
    Route::apiResource('rhythm',RhythmController::class);
    
});
Route::apiResource('chord',ChordController::class);

Route::apiResource('music',MusicController::class);
