<?php

namespace App\Http\Controllers\Api\App;

use App\Models\Music\Singer;
use App\Http\Controllers\Controller;
use App\Exceptions\Music\MusicException;
use App\Http\Requests\Api\Music\SingerRequest;
use App\Http\Resources\Api\Music\SingerResource;

class SingerController extends Controller
{
    public function index()
    {
        try{
            $singers = Singer::with('musics')->get();
            return SingerResource::collection($singers);
        }catch(\Exception $e){
            throw new MusicException("Erro ao listar musicos.");
        }
        
    }
    public function store(SingerRequest $request)
    {
        $fields = $request->validated();
        $singer = Singer::where('singer_name', $fields['singer_name'])->first();
        if ($singer instanceof Singer) {
            throw new MusicException('Musico/Grupo já foi cadastrado.');
            
        } else {
            $newSinger = Singer::create($fields);
            return new SingerResource($newSinger);
        }
        
    }
    public function update(SingerRequest $request, string $id)
    {
        $fields = $request->validated();
        $singer_validade = Singer::where('singer_name', $fields['singer_name'])->first();
        if( $singer_validade instanceof Singer && $singer_validade->id != (int)$id){
            throw new MusicException('Musico/Grupo já esta cadastrado.');
        }

        try{
            $singer = Singer::findOrFail($id);
            $singer->fill($fields);
            $singer->save();
            return new SingerResource($singer);
        }catch(\Exception $e){
            throw new MusicException('Erro ao atualizar Musico/Grupo.');

        }
    }
    public function destroy(string $id)
    {
        try{
            $singer = Singer::findOrFail($id);
            $singer->delete();
            return response()->json([],201);
        }catch(\Exception $e){
            throw new MusicException('Musico/Grupo não encontrado.');
        }
    }
}
