<?php

namespace App\Http\Controllers\Api\App;

use App\Models\Music\Music;
use Illuminate\Support\Str;
use App\Models\Music\Composer;
use App\Http\Controllers\Controller;
use App\Exceptions\Music\MusicException;
use App\Http\Requests\Api\Music\MusicRequest;
use App\Http\Resources\Api\Music\MusicResource;
use App\Http\Requests\Api\Music\MusicUpdateRequest;


class MusicController extends Controller
{
    public function index()
    {
        try {
            $musics = Music::with(['singer', 'tone', 'rhythm'])->get();
            return MusicResource::collection($musics);
        } catch (\Exception $e) {
            throw new MusicException('Erro ao listar músicas. ' . $e->getMessage());
        }
    }

    public function store(MusicRequest $request)
    {
        try {
            $fields = $request->validated();
            $fields['music_name'] = Str::upper($fields['music_name']);
            $fields['chords'] = json_encode($fields['chords']);

            $existingMusic = Music::where('music_name', $fields['music_name'])
                ->where('singer_id', $fields['singer_id'])
                ->first();

            if ($existingMusic instanceof Music) {
                throw new MusicException('Música já cadastrada.');
            }

            $newMusic = Music::create($fields);

            if (!empty($fields['composers'])) {
                $composerNames = $this->normalizeComposers($fields['composers']);
                $this->storeComposers($newMusic, $composerNames);
            }

            return new MusicResource($newMusic);
        } catch (\Exception $e) {
            throw new MusicException('Erro ao salvar música. ' . $e->getMessage());
        }
    }

    public function update(MusicUpdateRequest $request, string $id)
    {
        try {
            $fields = $request->validated();
            if($fields['music_name']){
                $fields['music_name'] = Str::upper($fields['music_name']);

            }
            if($fields['chords'] && $fields['chords'] != ""){
                $fields['chords'] = json_encode($fields['chords']);

            }

            $existingMusic = Music::where('music_name', $fields['music_name'])
                ->where('singer_id', $fields['singer_id'])
                ->where('id', '!=', $id)
                ->exists();

            if ($existingMusic) {
                throw new MusicException('Já existe outra música com esse nome e cantor.');
            }

            $music = Music::findOrFail($id);
            $music->fill($fields);
            $music->save();

            if (!empty($fields['composers'])) {
                $composerNames = $this->normalizeComposers($fields['composers']);
                $this->storeComposers($music, $composerNames);
            }

            return new MusicResource($music);
        } catch (\Exception $e) {
            throw new MusicException('Erro ao atualizar a música. ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $music = Music::findOrFail($id);
            $music->delete();
            return response()->json([], 201);
        } catch (\Exception $e) {
            throw new MusicException('Música não encontrada. ' . $e->getMessage());
        }
    }

    /**
     * Armazena os compositores vinculados à música,
     * atualizando corretamente a contagem de `amount_musics`.
     */
    private function storeComposers(Music $music, array $composerNames)
    {
        $newComposerIds = [];

        foreach ($composerNames as $name) {
            $composer = Composer::firstOrCreate(['name' => $name]);

            if (!$music->composers()->where('composer_id', $composer->id)->exists()) {
                $composer->increment('amount_musics');
            }

            $newComposerIds[] = $composer->id;
        }

        // Reduz `amount_musics` dos compositores removidos
        $oldComposerIds = $music->composers()->pluck('composer_id')->toArray();
        $removedComposerIds = array_diff($oldComposerIds, $newComposerIds);

        foreach ($removedComposerIds as $removedId) {
            $composer = Composer::find($removedId);
            if ($composer && $composer->amount_musics > 0) {
                $composer->decrement('amount_musics');
            }
        }

        $music->composers()->sync($newComposerIds);
    }

    /**
     * Normaliza os nomes dos compositores (uppercase + trim)
     */
    private function normalizeComposers(string $composerString): array
    {
        $parts = explode(',', $composerString);
        return array_map(fn($name) => strtoupper(trim($name)), $parts);
    }
}
