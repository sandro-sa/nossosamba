<?php
namespace App\Http\Controllers\Api\App;

use App\Models\Music\Music;
use App\Http\Controllers\Controller;
use App\Exceptions\Music\MusicException;
use App\Http\Requests\Api\Music\MusicRequest;
use App\Http\Resources\Api\Music\MusicResource;

class MusicController extends Controller
{
    public function index()
    {
        try{
            $musics = Music::all();
            $musics->load(['singer','tone','rhythm']);
            return MusicResource::collection($musics);

        }catch(\Exception $e){
            throw new MusicException('Erro ao listar musicas');
        } 
    }
    public function store(MusicRequest $request)
    {
        $fields = $request->validated();

        $fields['chords'] = json_encode($fields['chords']);
        $music = Music::where('music_name', $fields['music_name'])->first();
        if ($music instanceof Music) {
            throw new MusicException('Musica já foi cadastrado.');
            
        } else {
            $newMusic = Music::create($fields);
            return new MusicResource($newMusic);
        }
        
    }
    public function update(MusicRequest $request, string $id)
    {
        $fields = $request->validated();
        $music_validade = Music::where('music_name', $fields['music_name'])->first();
        if( $music_validade instanceof Music && $music_validade->id != (int)$id){
            throw new MusicException('Musica já esta cadastrado.');
        }
        try{
            $music = Music::findOrFail($id);
            $music->fill($fields);
            $music->save();
            return new MusicResource($music);
        }catch(\Exception $e){
            throw new MusicException('Erro ao atualizar Musica.');

        }
    }
    public function destroy(string $id)
    {
        try{
            $music = Music::findOrFail($id);
            $music->delete();
            return response()->json([],201);
        }catch(\Exception $e){
            throw new MusicException('Musica não encontrado.');
        }
    }
}
