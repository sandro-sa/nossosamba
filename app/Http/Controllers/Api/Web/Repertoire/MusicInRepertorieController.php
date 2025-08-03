<?php

namespace App\Http\Controllers\Api\Web\Repertoire;

use App\Models\Music\Music;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Repertoire\Repertoire;
use App\Http\Requests\Api\Repertoire\SongRequest;
use App\Http\Controllers\Api\Web\Repertoire\Service;
use App\Http\Requests\Api\Repertoire\SongUpdateRequest;


class MusicInRepertorieController extends Controller
{
    
 
    public function index(Request $request, Repertoire $repertoire)
    {     
        $musics = $repertoire->musics()->withPivot(['tom', 'position'])->get();

         
        $data = $musics->map(function ($music) {
            return [
                'music_id' => $music->id,
                'tom' => $music->pivot->tom,
           
            ];
        });

        $musics_array = [];
        $aux =0;
        foreach ($data as $key => $value) {
      
            if( $value['tom'] == 0){
                $service = new Service($value['music_id'],null);
            }else{
                $service = new Service($value['music_id'],$value['tom']);
            }

            $musics_array[$aux] = $service->index();
            $aux++;

        }
    
        return response()->json(["data"=>$musics_array],201);
    }
    public function store(SongRequest $request, Repertoire $repertoire)
    {

        $validated = $request->validated();

        if ($repertoire->musics()->where('music_id', $validated['music_id'])->exists()) {
            return response()->json(['message' => 'Esta música já está no repertório.'], 409);
        }

        // Obtém a próxima posição disponível
        $lastPosition = $repertoire->musics()->max('music_repertoire.position');
        $nextPosition = $lastPosition ? $lastPosition + 1 : 1;

        $repertoire->musics()->attach($validated['music_id'], [
            'tom' => $validated['tom'] ?? null,
            'position' => $nextPosition,
        ]);

        return response()->json(['message' => 'Música adicionada com sucesso.'], 201);
    }
    public function update(SongUpdateRequest $request, Repertoire $repertoire, Music $music)
    {
        $validated = $request->validated();

        $currentPivot = $repertoire->musics()->where('music_id', $music->id)->first();

        if (!$currentPivot) {
            return response()->json(['message' => 'Música não encontrada no repertório.'], 404);
        }

        $currentPosition = $currentPivot->pivot->position;
        $newPosition = $validated['position'];

        if ($newPosition != $currentPosition) {
        
            $musics = $repertoire->musics()->withPivot('position')->orderBy('music_repertoire.position')->get();

            $musics = $musics->filter(fn($m) => $m->id !== $music->id)->values();

            $musics->splice($newPosition - 1, 0, [$music]);

            foreach ($musics as $index => $m) {
                $repertoire->musics()->updateExistingPivot($m->id, [
                    'position' => $index + 1,
                ]);
            }
        }

        $repertoire->musics()->updateExistingPivot($music->id, [
            'tom' => $validated['tom'] ?? null,
        ]);

        return response()->json(['message' => 'Música atualizada com sucesso.']);
    }
    public function destroy(Repertoire $repertoire, Music $music)
    {

        if (!$repertoire->musics()->where('music_id', $music->id)->exists()) {
            return response()->json(['message' => 'Música não está no repertório.'], 404);
        }

        $repertoire->musics()->detach($music->id);

        return response()->json(['message' => 'Música removida do repertório.'], 204);
    } 
}
