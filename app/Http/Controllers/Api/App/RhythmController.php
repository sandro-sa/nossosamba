<?php

namespace App\Http\Controllers\Api\App;

use App\Models\Music\Rhythm;
use App\Http\Controllers\Controller;
use App\Exceptions\Music\MusicException;
use App\Http\Requests\Api\Music\RhythmRequest;
use App\Http\Resources\Api\Music\RhythmResource;

class RhythmController extends Controller
{
    public function index()
    {
        $rhythms = Rhythm::all();
        return RhythmResource::collection($rhythms);
    }
    public function store(RhythmRequest $request)
    {
        $fields = $request->validated();
        $rhythm = Rhythm::where('rhythm', $fields['rhythm'])->first();
        if ($rhythm instanceof Rhythm) {
            throw new MusicException('Ritmo já foi cadastrado.');
            
        } else {
            $newRhythm = Rhythm::create($fields);
            return new RhythmResource($newRhythm);
        }
        
    }
    public function update(RhythmRequest $request, string $id)
    {
        $fields = $request->validated();
        $rhythm_validade = Rhythm::where('rhythm', $fields['rhythm'])->first();
        if( $rhythm_validade instanceof Rhythm && $rhythm_validade->id != (int)$id)
        {
            throw new MusicException('Ritmo já esta cadastrado.');
        }
        try{
            $rhythm = Rhythm::findOrFail($id);
            $rhythm->fill($fields);
            $rhythm->save();
            return new RhythmResource($rhythm);
        }catch(\Exception $e){
            throw new MusicException('Erro ao atualizar Ritmo.');
        }
    }
    public function destroy(string $id)
    {
        try{
            $rhythm = Rhythm::findOrFail($id);
            $rhythm->delete();
            return response()->json([],201);
        }catch(\Exception $e){
            throw new MusicException('Ritmo não encontrado.');
        }
    }
}
