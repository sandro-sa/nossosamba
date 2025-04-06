<?php

namespace  App\Http\Controllers\Web\AppWeb;

use App\Models\Music\Chord;
use App\Models\Music\Music;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\App\ChordService\RetornaListaDeNomesEhPosicaoOndeInciaOhAcorde;

class ShowMusicController extends Controller
{
    private $escalaMaior = [
        "C" => 1,
        "Db" => 2, "D" => 3,
        "Eb" =>4 , "E" => 5, "F" =>6,
        "F#" => 7 , "G" => 8,
        "Ab" => 9 , "A" => 10, "Bb" => 11 , "B" => 12
    ];

    private $escalaMenor = [
        "C" => 1,
        "C#" => 2, "D" => 3,
        "Eb" =>4 , "E" => 5, "F" =>6,
        "F#" => 7 , "G" => 8,
        "G#" => 9 , "A" => 10, "Bb" => 11 , "B" => 12 , "B" => 12
    ];

    public function index($id, $posicaoDoNovoTom = null)
    {
        $music = Music::where('id',$id)->first();

        if(!$music){
            return view('error.not-found');
        }

        $tom = $this->retornaTom($music->tone->tone);
        $acordeEhMenor = $this->verificaSeAcordeEhMenor($music->tone->tone);

        $escala = null;
        if($acordeEhMenor){
            $escala = $this->escalaMenor;
        }else{
            $escala = $this->escalaMaior;

        }
        foreach ($escala as $chave => $valor) {
            if($tom == $chave){
                $posicao = $valor;
            }
        }

        $listaDeTons = RetornaListaDeNomesEhPosicaoOndeInciaOhAcorde::retornaListaDeNomesEhPosicaoOndeInciaOhAcorde($posicao, $music->tone->tone);
    
        $arrayChords = json_decode($music->chords);

        $listaDeAcordeParaMudarTom = false;

        if($posicaoDoNovoTom != null){
            if($posicaoDoNovoTom < $posicao ||$posicaoDoNovoTom > $posicao){
                $novaPosicao = $posicao - $posicaoDoNovoTom;
                $listaDeAcordeParaMudarTom = $this->retornaListaDeAcordeParaMudarTom($arrayChords, $novaPosicao, $acordeEhMenor);
                $arrayChords = $this->retornaNovosAcordes($arrayChords, $novaPosicao, $acordeEhMenor);
            }
        }

       
        $chords = Chord::whereIn('chord_name', $arrayChords)->get();

        $tons = [];
        $aux = 0;
        foreach ($listaDeTons as $tom => $posicao) {
            $tons[$aux]["tom"] = $tom;
            $tons[$aux]["posicao"] = $posicao;
            $aux++;
        }
        
        $novoTom = false;
        if($listaDeAcordeParaMudarTom){
           $novoTom = $this->retornaNovoTom($music->tone->tone,$listaDeAcordeParaMudarTom, $posicaoDoNovoTom);
        }
       
       $listaDeTons = json_encode($tons);
       $listaDeAcordeParaMudarTom = json_encode($listaDeAcordeParaMudarTom);
        
        $music->load(['tone','singer','rhythm']);
        return view('web.show-music',[
            'music' => $music,
            'chords' => $chords,
            'listaDeTons' => $listaDeTons,
            'listaDeAcordeParaMudarTom' => $listaDeAcordeParaMudarTom,
            "novoTom" => $novoTom,
        ]);
      
    }

    private function retornaTom($tomOriginal){
      
        $tom = substr($tomOriginal, 0, 1);
        $segundoCaracterDoAcorde = mb_substr($tomOriginal, 1, 1, 'UTF-8');
        if($segundoCaracterDoAcorde == "b" || $segundoCaracterDoAcorde == "#"){
            $tom = $tom.$segundoCaracterDoAcorde;
        }

        return $tom;
    }

    private function verificaSeAcordeEhMenor($nomeDoAcorde){

        $segundoCaracter = mb_substr($nomeDoAcorde, 1, 1, 'UTF-8');
        $terceiroCaractere = mb_substr($nomeDoAcorde, 2, 1, 'UTF-8');
        
        if($segundoCaracter == "m"|| $terceiroCaractere == "m"){
            return true;
        }

        return false;

    }

    private function retornaRestanteDoAcorde($tom){
      
        $restanteDoAcorde = substr($tom, 1);
        $sustenidoOuBemol = mb_substr($tom, 1, 1, 'UTF-8');
        if($sustenidoOuBemol == "b" || $tom == "#"){
            $restanteDoAcorde = substr($tom, 2);
        }
        return $restanteDoAcorde;
    }

    private function retornaNovaNota($novaPosicao, $nota, $acordeEhMenor){

        $posicao = null;
        $escala = [];
        if($acordeEhMenor){
            $escala = $this->escalaMenor;
        }else{
            $escala = $this->escalaMaior;
        }

        foreach ($escala as $chave => $valor) {
            if($nota == $chave){
                $posicao = $valor - $novaPosicao;
            }
        }

        if(!$posicao){
           $posicao = 1;
        }
        if($posicao < 0){
            $posicao = 12 - ($posicao *-1);
        }
        if($posicao > 12){
            $posicao = $posicao - 12;
        }
      
        foreach ($escala as $chave => $valor) {
            if($posicao == $valor){
                return $chave;
            }
        }

    }
    
    private function retornaListaDeAcordeParaMudarTom($arrayChords, $novaPosicao, $escalaEhMenor){
        
        foreach($arrayChords as $key => $value){
            $nota = $this->retornaTom($value);

            $novaNota = $this->retornaNovaNota($novaPosicao, $nota, $escalaEhMenor);

            $restanteDoAcorde = $this->retornaRestanteDoAcorde($value);
    
            $novosAcordes[$value] = $novaNota.$restanteDoAcorde;
        }

        return $novosAcordes;
    }

    private function retornaNovosAcordes($arrayChords, $novaPosicao,  $escalaEhMenor){

        foreach($arrayChords as $key => $value){
            
            $nota = $this->retornaTom($value);

            $novaNota = $this->retornaNovaNota($novaPosicao, $nota, $escalaEhMenor );

            $restanteDoAcorde = $this->retornaRestanteDoAcorde($value);

            $novosAcordes[ $key] = $novaNota.$restanteDoAcorde;
        }

        return $novosAcordes;
    }

    private function retornaNovoTom($tom, $arrayDeTons, $novaPosicao){

        foreach($arrayDeTons as $key => $value){
            if($tom == $key){
                return $value;
            }
        }
        
        if(array_key_exists($tom, $this->escalaMaior )){
            foreach($this->escalaMaior as $key => $chave){
                if($novaPosicao == $chave){
                    return $key;
                }
            }
    
        }

        if(array_key_exists($tom, $this->escalaMenor )){
            foreach($this->escalaMaior as $key => $chave){
                if($novaPosicao == $chave){
                    return $key;
                }
            }
    
        }
    }
}
