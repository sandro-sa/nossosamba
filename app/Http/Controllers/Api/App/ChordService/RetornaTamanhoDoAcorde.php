<?php

namespace App\Http\Controllers\Api\App\ChordService;

class RetornaTamanhoDoAcorde
{
    public static function retornaTamanhoDoAcorde($acorde)
    {
        $tamanhoDoAcorde = 0;
        foreach($acorde as $trastes){
            $colunaComCordaPressionada = false;
            foreach($trastes as $status){
                if($status != null && !$colunaComCordaPressionada){
                    $tamanhoDoAcorde++;
                    $colunaComCordaPressionada = true;
                }
            }
            
        }

        return $tamanhoDoAcorde;
    }
}
