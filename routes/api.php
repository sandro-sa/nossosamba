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
use App\Http\Controllers\Api\Web\Repertoire\SongController;
use App\Http\Controllers\Api\App\ShowSingerMusicsController;
use App\Http\Controllers\Api\Web\Repertoire\MusicInRepertorieController;
use App\Http\Controllers\Api\Web\Repertoire\RepertoireController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('all_singer',[AllSingersController::class,'index']);
Route::get('all_chord',[ShowChordsController::class,'index']);
Route::get('rhythm', [RhythmController::class, 'index']);
Route::get('tone',[ToneController::class, 'index']);
Route::post('repertoire/sort',[RepertoireController::class, 'createRepertoire']);
Route::get('repertoire/allRepertoire',[RepertoireController::class, 'allRepertoire']);

Route::get('/repertoires/{repertoire}/musics',[MusicInRepertorieController::class, 'index']);
Route::post('repertoires/{repertoire}/musics',[MusicInRepertorieController::class, 'store']);
Route::put('repertoires/{repertoire}/musics/{music}',[MusicInRepertorieController::class, 'update']);
Route::delete('repertoires/{repertoire}/musics/{music}',[MusicInRepertorieController::class, 'destroy']);

Route::middleware(['auth', 'web'])->group(function (){
    
    Route::apiResource('repertoires',RepertoireController::class);
    Route::apiResource('singer',SingerController::class);
    Route::get('singer_musics/{id}',ShowSingerMusicsController::class);
    Route::apiResource('tone',ToneController::class)->except(['index']);
    Route::apiResource('rhythm', RhythmController::class)->except(['index']);
    
});
Route::apiResource('chord',ChordController::class);
Route::apiResource('music',MusicController::class);
