<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\Api\App\ChordService\RetornaColunaOndeOhAcordeComeca;

class RetornaColunaOndeOhAcordeComecaTest extends TestCase
{
    private static $acordeSolMaiorCorreto = [

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
    private static $acordeLaMaiorcorreto = [
        "chord_name"=> "A",
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
                "coluna_2_corda_0"=> "2",
                "coluna_2_corda_1"=> "0",
                "coluna_2_corda_2"=> "0",
                "coluna_2_corda_3"=> "0",
                "coluna_2_corda_4"=> "0"
            ],
            [
                "coluna_3_corda_0"=> "",
                "coluna_3_corda_1"=> "",
                "coluna_3_corda_2"=> "",
                "coluna_3_corda_3"=> "",
                "coluna_3_corda_4"=> ""
            ],
            [
                "coluna_4_corda_0"=> "",
                "coluna_4_corda_1"=> "",
                "coluna_4_corda_2"=> "",
                "coluna_4_corda_3"=> "",
                "coluna_4_corda_4"=> ""
            ],
            [
                "coluna_5_corda_0"=> "",
                "coluna_5_corda_1"=> "",
                "coluna_5_corda_2"=> "",
                "coluna_5_corda_3"=> "",
                "coluna_5_corda_4"=> ""
            ]
        ]
    ];
    private static $acordeLaMaiorCorretoForaDaPosicao = [
        
        "chord_name"=> "A",
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
                "coluna_3_corda_0"=> "5",
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
    public function test_retornaColunaOndeOhAcordeComeca(): void
    {
        $resultado1 = RetornaColunaOndeOhAcordeComeca::retornaColunaOndeOhAcordeComeca(RetornaColunaOndeOhAcordeComecaTest::$acordeSolMaiorCorreto['chord_positions']);
        $this->assertEquals(3, $resultado1, 'O acorde deve comecar em 3');

        $resultado2 = RetornaColunaOndeOhAcordeComeca::retornaColunaOndeOhAcordeComeca(RetornaColunaOndeOhAcordeComecaTest::$acordeLaMaiorcorreto['chord_positions']);
        $this->assertEquals(2, $resultado2, 'O acorde deve comecar em 2');

        $resultado3 = RetornaColunaOndeOhAcordeComeca::retornaColunaOndeOhAcordeComeca(RetornaColunaOndeOhAcordeComecaTest::$acordeLaMaiorCorretoForaDaPosicao['chord_positions']);
        $this->assertEquals(2, $resultado2, 'O acorde deve comecar em 2');

    }
}