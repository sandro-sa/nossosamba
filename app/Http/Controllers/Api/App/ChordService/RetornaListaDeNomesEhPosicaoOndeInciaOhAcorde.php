<?php

namespace App\Http\Controllers\Api\App\ChordService;

class RetornaListaDeNomesEhPosicaoOndeInciaOhAcorde{

   

    public static function retornaListaDeNomesEhPosicaoOndeInciaOhAcorde( $posicao_onde_comeca_acorde, $nomeDoAcorde ){

        $escalaMaior = [
            "C"  => 1,  "Db" => 2, "D" => 3,
            "Eb" =>4 ,  "E"  => 5, "F"  => 6,
            "F#" => 7 , "G"  => 8, "Ab" => 9 ,
            "A"  => 10, "Bb" => 11 ,"B" => 12
        ];
    
        $escalaMenor = [
            "C" => 1,
            "C#" => 2, "D" => 3,
            "Eb" =>4 , "E" => 5, "F" =>6,
            "F#" => 7 , "G" => 8,
            "G#" => 9 , "A" => 10, "Bb" => 11 , "B" => 12
        ];
        $escalaVerificacao = [
            "C","D", "E", "F", "G", "A", "B"
        ];



        $escala = [];

        $tom = substr($nomeDoAcorde, 1);
        $sustenidoOuBemol = mb_substr($nomeDoAcorde, 1, 1, 'UTF-8');
        $terceiroCaractere = mb_substr($nomeDoAcorde, 2, 1, 'UTF-8');
        if($sustenidoOuBemol == "b" || $sustenidoOuBemol == "#"){
            $tom = substr($nomeDoAcorde, 2);
        }

        if($sustenidoOuBemol == "m"|| $terceiroCaractere == "m"){
            $escala = $escalaMenor;
        }else{
            $escala = $escalaMaior;
        }

        $contémLetras = false;

        foreach ($escalaVerificacao as $letra) {
            if (stripos($tom, $letra) !== false) {
                $contémLetras = true;
                break;
            }
        }

        $nova__lista_de_acordes = [];
        if ($contémLetras) {
            $nova__lista_de_acordes[$nomeDoAcorde] = $posicao_onde_comeca_acorde;
            return $nova__lista_de_acordes;
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