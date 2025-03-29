<?php

namespace App\Http\Controllers\Api\App\ChordService;

class RetornaListaDeAcordes
{

    public static function retornaListaDeAcordes($listaComNomeEhPosicaoDoAcorde, $acorde,$tamanhoDoAcorde)
    {
        $acordes = [];
        $listaDeAcordes = [];
        $vetor = 0;
        foreach ($listaComNomeEhPosicaoDoAcorde as $nomeDoAcorde => $ondeIniciaAcorde) {
            if(($tamanhoDoAcorde + $ondeIniciaAcorde)<= 5){
               $acordes[$nomeDoAcorde] = RetornaAcordaNaPosicao::retornaAcordaNaPosicao($acorde, $ondeIniciaAcorde);
            
            }else{
                $acordes[$nomeDoAcorde] = RetornaAcordeNoInicioDaEscala::retornaAcordeNoInicioDaEscala($acorde, $tamanhoDoAcorde, $ondeIniciaAcorde);
            }

            $listaDeAcordes[$vetor]["chord_name"] = $nomeDoAcorde;
            $listaDeAcordes[$vetor]["chord_positions"] = RetornaAcordeOrganizado::retornaAcordeOrganizado($acordes[$nomeDoAcorde]);
            $vetor++;
        }

        return $listaDeAcordes;
    }

    
}


$chord = [
    "chord_name"=> "G",
    "chord_positions"=> [
        [
            "coluna_0_corda_0"=> "",
            "coluna_0_corda_1"=> "",
            "coluna_0_corda_2"=> "",
            "coluna_0_corda_3"=> "",
            "coluna_0_corda_4"=> ""
        ],
        [
            "coluna_1_corda_0"=> "",
            "coluna_1_corda_1"=> "",
            "coluna_1_corda_2"=> "",
            "coluna_1_corda_3"=> "",
            "coluna_1_corda_4"=> ""
        ],
        [
            "coluna_2_corda_0"=> "",
            "coluna_2_corda_1"=> "",
            "coluna_2_corda_2"=> "",
            "coluna_2_corda_3"=> "",
            "coluna_2_corda_4"=> ""
        ],
        [
            "coluna_3_corda_0"=> "3",
            "coluna_3_corda_1"=> "",
            "coluna_3_corda_2"=> "",
            "coluna_3_corda_3"=> "1",
            "coluna_3_corda_4"=> ""
        ],
        [
            "coluna_4_corda_0"=> "",
            "coluna_4_corda_1"=> "",
            "coluna_4_corda_2"=> "2",
            "coluna_4_corda_3"=> "",
            "coluna_4_corda_4"=> ""
        ],
        [
            "coluna_5_corda_0"=> "",
            "coluna_5_corda_1"=> "3",
            "coluna_5_corda_2"=> "",
            "coluna_5_corda_3"=> "",
            "coluna_5_corda_4"=> "4"
        ]
    ]
];



  