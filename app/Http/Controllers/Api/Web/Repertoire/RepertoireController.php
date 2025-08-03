<?php

namespace App\Http\Controllers\Api\Web\Repertoire;

use App\Models\Music\Music;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Repertoire\Repertoire;
use App\Exceptions\Music\MusicException;
use App\Http\Resources\Api\Music\MusicResource;
use App\Http\Requests\Api\Repertoire\RepertoireRequest;
use App\Http\Resources\Api\Repertoire\RepertoireResource;
use App\Http\Requests\Api\Repertoire\RepertoireUpdateRequest;


class RepertoireController extends Controller
{
    
    public function index()
    {
        $user = Auth::user();
        $repertoires = Repertoire::where("user_id", $user->id)->get();
        return RepertoireResource::collection($repertoires);
    }

    public function store(RepertoireRequest $request)
    {
        $validated = $request->validated();
        try {
            $validated['user_id'] = $request->user()->id;
            $repertoire = Repertoire::create($validated);

        } catch (\Throwable $th) {
            throw new MusicException('Erro ao cadastrar repertório.');
        }

        return new RepertoireResource($repertoire);
    }

    public function update(RepertoireUpdateRequest $request, Repertoire $repertoire)
    {
        if ($repertoire->user_id !== $request->user()->id) {
            abort(403, 'Acesso não autorizado a este repertório.');
        }

        $validated = $request->validated();
        try {
            $repertoire->fill($validated);
            $repertoire->save();

        } catch (\Throwable $th) {
            throw new MusicException('Erro ao atualizar repertório.');
        }

        return new RepertoireResource($repertoire);
    }

    public function destroy( Repertoire $repertoire)
    {
         if ($repertoire->user_id !== auth()->user()->id) {
            abort(403, 'Acesso não autorizado a este repertório.');
        }

        try {
            $repertoire->delete();
        } catch (\Throwable $th) {
           throw new MusicException('Erro ao excluir repertório.');
        }
        
         return response()->json(null, 204);
    }

    public function createRepertoire(Request $request)
    {
        $rhythm_id = $request["rhythm_id"];
        $tone_id = $request["tone_id"];
        $musics = Music::where('rhythm_id', $rhythm_id)->inRandomOrder();

        if ($tone_id) {
            $musics->where('tone_id', $tone_id)->inRandomOrder();
        } else {
            $musics->orderBy('tone_id')->inRandomOrder();
        }

        try {
            $musics = $musics->get();
            $musics->load(['singer', 'tone', 'rhythm']);
            return MusicResource::collection($musics);

        } catch (\Exception $e) {
            throw new MusicException('Erro ao listar músicas');
        }
    }

      public function allRepertoire()
    {
        $repertoires = Repertoire::all();
        $repertoires->load('musics');
        return RepertoireResource::collection($repertoires);
    }

}
