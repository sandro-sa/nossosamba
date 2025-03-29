<?php

namespace App\Http\Controllers\Api\App\ChordService;

class RetornaColunaOndeOhAcordeComeca{

    public static function retornaColunaOndeOhAcordeComeca($acorde){
        $ondeAcordeComeca = -1;
        $traste = 0;
        $temMaisDeUmIncioDeAcorde =0;
        foreach($acorde as $trastes){
            foreach($trastes as $corda => $status){
                if($corda == "coluna_".$traste."_corda_0" && ($status != null || $status != "")){
                        $ondeAcordeComeca = $status;
                        $temMaisDeUmIncioDeAcorde++;
                    
                    
                }
                
            }
            $traste++;
        }

        if($temMaisDeUmIncioDeAcorde != 1){
            $ondeAcordeComeca = -1;
        }
        return $ondeAcordeComeca;
    }
}