<?php
namespace App\Http\Controllers\Api\App;

use App\Models\Music\Music;
use Illuminate\Support\Str;
use App\Models\Music\Composer;
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

        $fields['music_name'] = Str::upper($fields['music_name'] );
        $fields['chords'] = json_encode($fields['chords']);
        $music = Music::where('music_name', $fields['music_name'])->where('singer_id', $fields['singer_id'])->first();
        if ($music instanceof Music) {
            throw new MusicException('Musica já foi cadastrado.');
            
        } else {
            $newMusic = Music::create($fields);
            if($fields['composers']){
                $composers = $fields['composers'];
                $composersArray = explode(", ", $composers);
                $composersArray = array_map('strtoupper', $composersArray);
                $this->storeComposers($newMusic,$composersArray, false);
            }
            return new MusicResource($newMusic);
        }
        
    }
    public function update(MusicRequest $request, string $id)
    {
        $fields = $request->validated();
        $fields['music_name'] = Str::upper($fields['music_name'] );
        $composers = $fields['composers'];
        $composersArray = explode(", ", $composers);
        $composersArray = array_map('strtoupper', $composersArray);
        $music_validade = Music::where('music_name', $fields['music_name'])->first();
        if ($music_validade instanceof Music && $music_validade->id == (int)$id) {
            try {
                $music = Music::where('id',$id)->first();
                $fields['chords'] = json_encode($fields['chords']);
                $music->fill($fields);
                $music->save();
                $this->storeComposers($music, $composersArray, true);
                return new MusicResource($music);
            } catch (\Exception $e) {
                throw new MusicException('Erro ao atualizar a música.');
            }
        }else{
            throw new MusicException('Erro ao atualizar a música.');
        }
    }
    public function destroy(string $id)
    {
        try{
            $music = Music::findOrFail($id);
            $music->delete();
            return response()->json([],201);
        }catch(\Exception $e){
            throw new MusicException('Musica não encontrada.');
        }
    }
    private function storeComposers(Music $music, $composers, $update)
    {
        $composers_id = [];
        
        foreach ($composers as $composerName) {
            $exitsComposer = Composer::where('name', $composerName)->first();
            
            if ($exitsComposer) {
                if (!$update && Music::where('music_name', $music->music_name)->count() == 1) {
                    $exitsComposer->amount_musics += 1;
                    $exitsComposer->save();
                }
                $composers_id[] = $exitsComposer->id;
            } else {
                $newComposer = Composer::create(['name' => $composerName]);
                $composers_id[] = $newComposer->id;
            }
        }
        $music->composers()->sync($composers_id);
    }
}
