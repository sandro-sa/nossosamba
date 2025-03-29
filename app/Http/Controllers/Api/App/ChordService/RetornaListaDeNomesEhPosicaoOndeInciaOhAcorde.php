<?php

namespace App\Http\Controllers\Api\App\ChordService;

class RetornaListaDeNomesEhPosicaoOndeInciaOhAcorde{

    public static function retornaListaDeNomesEhPosicaoOndeInciaOhAcorde( $posicao_onde_comeca_acorde, $nomeDoAcorde ){
        $escala = [
            "C" => 1, "C#" =>2,
            "Db" => 2, "D" => 3, "D#" => 4,
            "Eb" =>4 , "E" => 5, "F" =>6,
            "F#" => 7 , "Gb" => 7, "G" => 8,
            "G#" => 9, "Ab" => 9 , "A" => 10 ,
            "A#" => 11, "Bb" => 11 , "B" => 12
        ];

        $tom = substr($nomeDoAcorde, 1);
        $sustenidoOuBemol = mb_substr($nomeDoAcorde, 1, 1, 'UTF-8');
        if($sustenidoOuBemol == "b" || $sustenidoOuBemol == "#"){
            $tom = substr($nomeDoAcorde, 2);
        }
        $lista_de_acordes = [];
        $nova__lista_de_acordes = [];
        foreach ($escala as $chave => $valor) {
            $novo_acorde = $chave . $tom;
            $lista_de_acordes[$novo_acorde] = $valor;
        }

        $numero = 0;
        foreach ($lista_de_acordes as $chave => $valor) {
            if($nomeDoAcorde == $chave){
               $numero =  $posicao_onde_comeca_acorde - $valor;
            }
        }

        foreach ($lista_de_acordes as $chave => $valor) {
            $novo_acorde = $chave;
            $nova_chave = $valor + ($numero);
            if($nova_chave > 12){
                $nova__lista_de_acordes[$novo_acorde] = $nova_chave - 12;
            }elseif($nova_chave < 0){
                $nova__lista_de_acordes[$novo_acorde] = $nova_chave + 12;
            }else{
                $nova__lista_de_acordes[$novo_acorde] = $nova_chave;
            }
            
        }
   
    
        asort($nova__lista_de_acordes);
        return $nova__lista_de_acordes;

    }
}