<?php

namespace App\Http\Controllers\Api\App\ChordService;

use App\Http\Controllers\Api\App\ChordService\PreencheTrastesQueFaltam;

class RetornaAcordeNoInicioDaEscala{

    public static function retornaAcordeNoInicioDaEscala($acorde, $tamanhoDoAcorde, $posicaoDoNovoAcorde){
        $coluna_que_inicia_o_acorde = 1;
        $novo_acorde = [];
        $coluna_onde_inicia_o_acorde_esta_preenchida = false;

            foreach ($acorde as $key => $coluna_cordas) {
                $coluna_iniciada = false;
                $numero_da_corda = 0;
                foreach ($coluna_cordas as $key => $corda) {
                    $inicio = false;
                    
                        if(!$coluna_onde_inicia_o_acorde_esta_preenchida && $numero_da_corda == 0 && $corda != null){
                            $novo_acorde[$coluna_que_inicia_o_acorde]["coluna_".$coluna_que_inicia_o_acorde."_corda_".$numero_da_corda] = $posicaoDoNovoAcorde;
                            $coluna_onde_inicia_o_acorde_esta_preenchida = true;
                            $coluna_iniciada = true;
                            $inicio= true;
                        }
                        if($coluna_onde_inicia_o_acorde_esta_preenchida && !$inicio){
                            $novo_acorde[$coluna_que_inicia_o_acorde]["coluna_".$coluna_que_inicia_o_acorde."_corda_".$numero_da_corda] = $corda;
                            $coluna_iniciada= true;
                        }
                    

                    $numero_da_corda++;
                }
                if( $coluna_iniciada){
                    $coluna_que_inicia_o_acorde++;
                }
            }
        $novo_acorde = PreencheTrastesQueFaltam::preencheTrastesQueFaltam($novo_acorde);
        return $novo_acorde;
    }
}