<?php

namespace App\Http\Controllers\Api\App;

use App\Models\Music\Music;
use Illuminate\Http\Request;
use App\Models\Music\Composer;
use App\Http\Controllers\Controller;
use App\Exceptions\Music\MusicException;
use App\Http\Resources\Api\Music\MusicResource;
use App\Http\Resources\Api\Music\ComposerResource;

class ComposerController extends Controller
{
    public function index()
    {
        $composers = Composer::with('musics')->orderBy('name', 'asc')->get();
        return ComposerResource::collection($composers);
    }

    public function showComposerMusic(string $music)
    {
        try{
            $music = Music::where('musuc_name',$music)->first();
            return new MusicResource($music);
        }catch(\Exception $e){
            throw new MusicException('Musica não encontrada.');
        }
    }
}
