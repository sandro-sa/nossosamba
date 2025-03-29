<?php

namespace App\Http\Controllers\Api\App\ChordService;

class RetornaSeAhTrasteOndeComecaAcordeEstaNaPosicaoCerta{
    
    public static function retornaSeAhTrasteOndeComecaAcordeEstaNaPosicaoCerta($acorde){
        $ondeAcordeComeca = 0;
        $traste = 0;
        $encotrouOndeIniciaOhAcorde = false;
        foreach($acorde as $trastes){
            foreach($trastes as $corda => $status){
                if($corda == "coluna_".$traste."_corda_0" && $status != null){
                    if(!$encotrouOndeIniciaOhAcorde && $status == $traste){
                        $encotrouOndeIniciaOhAcorde = true;
                        $ondeAcordeComeca = true;
                    }else{
                        $encotrouOndeIniciaOhAcorde = true;
                        return false;
                    }
                }
                
            }
            $traste++;
        }
        return $ondeAcordeComeca;
    }

}