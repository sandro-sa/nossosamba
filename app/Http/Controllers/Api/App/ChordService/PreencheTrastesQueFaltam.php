<?php

namespace App\Http\Controllers\Api\App\ChordService;

class PreencheTrastesQueFaltam
{
    public static function preencheTrastesQueFaltam($acorde){
       
        $acordePeenchido = $acorde;
      
        for($i = 0; $i <= 5; $i++){
           if(!array_key_exists($i, $acorde)){
               $acordePeenchido[$i]["coluna_".$i."_corda_0"] = null;
               $acordePeenchido[$i]["coluna_".$i."_corda_1"] = null;
               $acordePeenchido[$i]["coluna_".$i."_corda_2"] = null;
               $acordePeenchido[$i]["coluna_".$i."_corda_3"] = null;
               $acordePeenchido[$i]["coluna_".$i."_corda_4"] = null;
            }
        }
        ksort($acordePeenchido);
        return $acordePeenchido;
    }

}