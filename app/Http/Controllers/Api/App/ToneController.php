<?php

namespace App\Http\Controllers\Api\App;

use App\Models\Music\Tone;
use App\Http\Controllers\Controller;
use App\Exceptions\Music\MusicException;
use App\Http\Requests\Api\Music\ToneRequest;
use App\Http\Resources\Api\Music\ToneResource;

class ToneController extends Controller
{
    public function index()
    {
        $tone = Tone::all();
        return ToneResource::collection($tone);
    }
    public function store(ToneRequest $request)
    {
        $fields = $request->validated();
        $tone = Tone::where('tone', $fields['tone'])->first();
        if ($tone instanceof Tone) {
            throw new MusicException('Tom já foi cadastrado.');
            
        } else {
            $newtone = Tone::create($fields);
            return new ToneResource($newtone);
        }
    }
    public function update(ToneRequest $request, string $id)
    {
        $fields = $request->validated();
        $tone_validade = Tone::where('tone', $fields['tone'])->first();
        if( $tone_validade instanceof Tone && $tone_validade->id != (int)$id){
            throw new MusicException('Tom já esta cadastrado.');
        }

        try{
            $tone = Tone::findOrFail($id);
            $tone->fill($fields);
            $tone->save();
            return new ToneResource($tone);
        }catch(\Exception $e){
            throw new MusicException('Erro ao atualizar Tom.');

        }
    }
    public function destroy(string $id)
    {
        try{
            $tone = Tone::findOrFail($id);
            $tone->delete();
            return response()->json([],201);
        }catch(\Exception $e){
            throw new MusicException('Tom não encontrado.');
        }
    }
}
