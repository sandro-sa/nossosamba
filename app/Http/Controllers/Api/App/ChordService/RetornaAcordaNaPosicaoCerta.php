<?php

namespace App\Http\Controllers\Api\App\ChordService;

class RetornaAcordaNaPosicaoCerta{

    public static function retornaAcordaNaPosicaoCerta($acorde){

        $traste = 0;
        $encotrouOndeIniciaOhAcorde = false;
        $acordeNaPosisaoCerta =[];
        foreach($acorde as $trastes){
            $cordaNumero = 0;
            foreach($trastes as $corda => $status ){
                $controleAuxiliar = true;
                if($corda == "coluna_".$traste."_corda_0" && $status != null && !$encotrouOndeIniciaOhAcorde){
                    $encotrouOndeIniciaOhAcorde = true;
                    $traste = $status;
                    $acordeNaPosisaoCerta[$traste]["coluna_".$traste."_corda_".$cordaNumero] = $status;
                    $controleAuxiliar = false;
                }
                if($encotrouOndeIniciaOhAcorde && $controleAuxiliar){
                    $acordeNaPosisaoCerta[$traste]["coluna_".$traste."_corda_".$cordaNumero] = $status;
                   
                }
                $cordaNumero++;
                
            }
            $traste++;
            
        }
        //dd($acordeNaPosisaoCerta);
        return $acordeNaPosisaoCerta;
    }
    
}


// $acordeSolMaiorCorreto = [

//     "chord_name"=> "F#",
//     "chord_positions"=> [
//         [
//             "coluna_0_corda_0"=> "",
//             "coluna_0_corda_1"=> "",
//             "coluna_0_corda_2"=> "",
//             "coluna_0_corda_3"=> "",
//             "coluna_0_corda_4"=> ""
//         ],
//         [
//             "coluna_1_corda_0"=> "",
//             "coluna_1_corda_1"=> "",
//             "coluna_1_corda_2"=> "",
//             "coluna_1_corda_3"=> "",
//             "coluna_1_corda_4"=> ""
//         ],
//         [
//             "coluna_2_corda_0"=> "",
//             "coluna_2_corda_1"=> "",
//             "coluna_2_corda_2"=> "",
//             "coluna_2_corda_3"=> "",
//             "coluna_2_corda_4"=> ""
//         ],
//         [
//             "coluna_3_corda_0"=> "2",
//             "coluna_3_corda_1"=> "",
//             "coluna_3_corda_2"=> "",
//             "coluna_3_corda_3"=> "1",
//             "coluna_3_corda_4"=> ""
//         ],
//         [
//             "coluna_4_corda_0"=> "",
//             "coluna_4_corda_1"=> "",
//             "coluna_4_corda_2"=> "2",
//             "coluna_4_corda_3"=> "",
//             "coluna_4_corda_4"=> ""
//         ],
//         [
//             "coluna_5_corda_0"=> "",
//             "coluna_5_corda_1"=> "3",
//             "coluna_5_corda_2"=> "",
//             "coluna_5_corda_3"=> "",
//             "coluna_5_corda_4"=> "4"
//         ]
//     ]
// ];




// print_r(RetornaAcordaNaPosicaoCerta::retornaAcordaNaPosicaoCerta($acordeSolMaiorCorreto['chord_positions'])) ;