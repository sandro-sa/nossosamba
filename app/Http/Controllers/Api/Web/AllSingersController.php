<?php

namespace  App\Http\Controllers\Api\Web;

use App\Models\Music\Singer;
use App\Http\Controllers\Controller;
use App\Exceptions\Music\MusicException;
use App\Http\Resources\Api\Music\SingerResource;

class AllSingersController extends Controller
{
    public function index()
    {
        try{
            $singers = Singer::with('musics')->orderBy('singer_name')->get();
            return SingerResource::collection($singers);
        }catch(\Exception $e){
            throw new MusicException("Erro ao listar musicos.");
        }
        
    }
}
