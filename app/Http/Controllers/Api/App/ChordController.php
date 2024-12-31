<?php

namespace App\Http\Controllers\Api\App;

use App\Exceptions\Music\MusicException;
use App\Models\Music\Chord;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Music\ChordResource;

class ChordController extends Controller
{
    public function index()
    {
        $chords = Chord::orderBy('chord_name', 'asc')->get();
        return ChordResource::collection($chords);
    }
    public function store(Request $request)
    {
        $fileds = $request->all();
        $chord = Chord::where('chord_name', $fileds['chord_name'])->first();
        if ($chord instanceof Chord) {
            $existingPositions = json_decode($chord->chord_positions, true);
            $newPosition = $fileds['chord_positions'];
            foreach ($existingPositions as $position) {
                if ($position == $newPosition) {
                    throw new MusicException('As posições do acorde já existem.');
                }
            }
            
            array_push($existingPositions,$newPosition);
            $chord->chord_positions = json_encode($existingPositions);
            $chord->save();
            return new ChordResource($chord);
        } else {
            $fileds['chord_positions'] = json_encode([$fileds['chord_positions']]);
            $newChord = Chord::create($fileds);
            return new ChordResource($newChord);
        }
        
    }
    public function destroy(Request $request,string $id)
    {
        $fileds = $request->all();
        $chord = Chord::findOrFail($id);
        if ($chord instanceof Chord) {
            $existingPositions = json_decode($chord->chord_positions, true);
            $key = (int)$fileds['key'];
            if(count($existingPositions) > 1)
            {
                unset($existingPositions[$key]);
                $existingPositions = array_values($existingPositions);
                $chord->chord_positions = json_encode($existingPositions);
                $chord->save();
             
            }else {
                $chord->delete();
                return response()->json([],201);
            }
        }else{
            throw new MusicException('Acorde não encontrado');
        }
    }
}
