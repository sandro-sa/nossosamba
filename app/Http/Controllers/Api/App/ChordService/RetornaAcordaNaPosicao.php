<?php

namespace App\Http\Controllers\Api\App\ChordService;

class RetornaAcordaNaPosicao{

    public static function retornaAcordaNaPosicao($acorde, $posicao){

        $traste = 0;
        $encotrouOndeIniciaOhAcorde = false;
        $acordeNaPosisaoCerta =[];
        $novaPosicao = $posicao;
        
        foreach($acorde as $trastes){
            $cordaNumero = 0;
            foreach($trastes as $corda => $status ){
                $controleAuxiliar = true;
                
                if($corda == "coluna_".$traste."_corda_0" && $status != null && !$encotrouOndeIniciaOhAcorde){
                    $encotrouOndeIniciaOhAcorde = true;
                    $traste = $novaPosicao;
                    $acordeNaPosisaoCerta[$traste]["coluna_".$traste."_corda_".$cordaNumero] = (string)$novaPosicao;
                    $controleAuxiliar = false;

                }
                if($encotrouOndeIniciaOhAcorde && $controleAuxiliar){
                    $acordeNaPosisaoCerta[$traste]["coluna_".$traste."_corda_".$cordaNumero] = $status;
                   
                }
                $cordaNumero++;

            }
            $traste++;
            
        }

        $acordeNaPosisaoCerta = PreencheTrastesQueFaltam::preencheTrastesQueFaltam($acordeNaPosisaoCerta);
    
        return $acordeNaPosisaoCerta;
    }
    
}


