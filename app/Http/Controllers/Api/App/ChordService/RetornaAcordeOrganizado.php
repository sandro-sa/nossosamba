<?php

namespace App\Http\Controllers\Api\App\ChordService;

class RetornaAcordeOrganizado
{
    public static function retornaAcordeOrganizado($acorde){
       
        $coluna_1_corda_0 = false;
        $coluna_2_corda_1 = false;
        $coluna_3_corda_2 = false;
        $coluna_4_corda_3 = false;
        $coluna_5_corda_4 = false;
    
        
        foreach($acorde as $chave => $valor){
            
           
            if(array_key_exists("coluna_1_corda_1", $valor)) {
                $coluna_1_corda_0 = true;
            }elseif(array_key_exists("coluna_2_corda_1", $valor)) {
                $coluna_2_corda_1 = true;
            }elseif(array_key_exists("coluna_3_corda_2", $valor)) {
                $coluna_3_corda_2 = true;
            }elseif(array_key_exists("coluna_4_corda_3", $valor)) {
                $coluna_4_corda_3 = true;
            }elseif(array_key_exists("coluna_5_corda_4", $valor)) {
                $coluna_5_corda_4 = true;
            }elseif(array_key_exists("coluna_0_corda_0", $valor)) {
                unset($acorde[0]);
            }elseif(array_key_exists("coluna_6_corda_0", $valor)) {
                unset($acorde[6]);
            }elseif(array_key_exists("coluna_7_corda_0", $valor)) {
                unset($acorde[7]);
            }elseif(array_key_exists("coluna_8_corda_0", $valor)) {
                unset($acorde[8]);
            }elseif(array_key_exists("coluna_9_corda_0", $valor)) {
                unset($acorde[9]);
            }elseif(array_key_exists("coluna_10_corda_0", $valor)) {
                unset($acorde[10]);
            }
          
        }
    
        if(!$coluna_1_corda_0) {
            $acorde[1]["coluna_1_corda_0"] = null;
            $acorde[1]["coluna_1_corda_1"] = null;
            $acorde[1]["coluna_1_corda_2"] = null;
            $acorde[1]["coluna_1_corda_3"] = null;
            $acorde[1]["coluna_1_corda_4"] = null;
        }
        if(!$coluna_2_corda_1) {
            $acorde[2]["coluna_2_corda_0"] = null;
            $acorde[2]["coluna_2_corda_1"] = null;
            $acorde[2]["coluna_2_corda_2"] = null;
            $acorde[2]["coluna_2_corda_3"] = null;
            $acorde[2]["coluna_2_corda_4"] = null;
        }
        if(!$coluna_3_corda_2) {
            $acorde[3]["coluna_3_corda_0"] = null;
            $acorde[3]["coluna_3_corda_1"] = null;
            $acorde[3]["coluna_3_corda_2"] = null;
            $acorde[3]["coluna_3_corda_3"] = null;
            $acorde[3]["coluna_3_corda_4"] = null;
        }
        if(!$coluna_4_corda_3)  {
            $acorde[4]["coluna_4_corda_0"] = null;
            $acorde[4]["coluna_4_corda_1"] = null;
            $acorde[4]["coluna_4_corda_2"] = null;
            $acorde[4]["coluna_4_corda_3"] = null;
            $acorde[4]["coluna_4_corda_4"] = null;
        }
        if(!$coluna_5_corda_4)  {
            $acorde[5]["coluna_5_corda_0"] = null;
            $acorde[5]["coluna_5_corda_1"] = null;
            $acorde[5]["coluna_5_corda_2"] = null;
            $acorde[5]["coluna_5_corda_3"] = null;
            $acorde[5]["coluna_5_corda_4"] = null;
        }
        ksort($acorde);
        return $acorde;
    }

}

//"Ebmb9"