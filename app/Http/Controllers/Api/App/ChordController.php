<?php

namespace App\Http\Controllers\Api\App;

use App\Models\Music\Chord;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exceptions\Music\MusicException;
use App\Http\Resources\Api\Music\ChordResource;
use App\Http\Controllers\Api\App\ChordService\RetornaListaDeAcordes;
use App\Http\Controllers\Api\App\ChordService\RetornaTamanhoDoAcorde;
use App\Http\Controllers\Api\App\ChordService\PreencheTrastesQueFaltam;
use App\Http\Controllers\Api\App\ChordService\RetornaAcordaNaPosicaoCerta;
use App\Http\Controllers\Api\App\ChordService\RetornaColunaOndeOhAcordeComeca;
use App\Http\Controllers\Api\App\ChordService\RetornaListaDeNomesEhPosicaoOndeInciaOhAcorde;
use App\Http\Controllers\Api\App\ChordService\RetornaSeAhTrasteOndeComecaAcordeEstaNaPosicaoCerta;



class ChordController extends Controller{

    public function index()
    {
        $chords = Chord::orderBy('chord_name', 'asc')->get();
        return ChordResource::collection($chords);
    }
    public function store(Request $request)
    {
        $fields = $request->all();

        $nomeDoAcorde = $fields['chord_name'];
        $acorde = $fields['chord_positions'];
        $tamanhoDoAcorde = RetornaTamanhoDoAcorde::retornaTamanhoDoAcorde($acorde);
        $posicaoOndeComecaArcorde = RetornaColunaOndeOhAcordeComeca::retornaColunaOndeOhAcordeComeca($acorde);

        if($posicaoOndeComecaArcorde == -1 ){
            throw new MusicException('Erro na posição onde o acorde inicia');
        }

        $acordeEstaNaMesmaPosicaoDaTraste = RetornaSeAhTrasteOndeComecaAcordeEstaNaPosicaoCerta::retornaSeAhTrasteOndeComecaAcordeEstaNaPosicaoCerta($acorde);

        if(!$acordeEstaNaMesmaPosicaoDaTraste && $posicaoOndeComecaArcorde < 5){
            $acorde = RetornaAcordaNaPosicaoCerta::retornaAcordaNaPosicaoCerta($acorde);
            $acorde = PreencheTrastesQueFaltam::preencheTrastesQueFaltam($acorde);
        }

        $listaComNomeEhPosicaoDoAcorde = RetornaListaDeNomesEhPosicaoOndeInciaOhAcorde::retornaListaDeNomesEhPosicaoOndeInciaOhAcorde($posicaoOndeComecaArcorde, $nomeDoAcorde);
       
        $listaDeAcordes = RetornaListaDeAcordes::retornaListaDeAcordes($listaComNomeEhPosicaoDoAcorde, $acorde, $tamanhoDoAcorde);


        $sucesso = 0;
        foreach($listaDeAcordes as $chave => $acorde){
           $resposta = ChordController::storeAcorde($acorde);
           if($resposta){
                $sucesso++;
            }
        }

        if($sucesso == 0){
            throw new MusicException('Acorde já existe');
        }
    
        return response()->json(["data"=>"sucess","message"=>$sucesso],201);

    }

    private static function storeAcorde($acorde)
    {

        $chord = Chord::where('chord_name', $acorde['chord_name'])->first();

        if ($chord instanceof Chord) {
            $existingPositions = json_decode($chord->chord_positions, true);

            $newPosition = $acorde['chord_positions'];
            foreach ($existingPositions as $position) {
                if ($position == $newPosition) {
                    return false;
                }
            }
            
            array_push($existingPositions,$newPosition);
            $chord->chord_positions = json_encode($existingPositions);
            $chord->save();
            return true;

        } else {
            $acorde['chord_positions'] = json_encode([$acorde['chord_positions']]);
            Chord::create($acorde);
            return true;
        }
        
    }
    
    public function destroy(Request $request,string $id)
    {
        $fields = $request->all();
        $chord = Chord::findOrFail($id);
        if ($chord instanceof Chord) {
            $existingPositions = json_decode($chord->chord_positions, true);
            $key = (int)$fields['key'];
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
