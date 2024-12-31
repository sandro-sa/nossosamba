<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ChordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('chords')->insert([
            [
                'id' => 1,
                'chord_name' => "C",
                'chord_positions' =>json_encode([ [
                        ['line_1_position_1'=> null,'line_1_position_2'=>null,'line_1_position_3'=>null,'line_1_position_4'=>'1','line_1_position_5'=>null],
                        ['line_2_position_1'=>'','line_2_position_2'=>'2','line_2_position_3'=>'','line_2_position_4'=>'','line_2_position_5'=>'3'],
                        ['line_3_position_1'=>'','line_3_position_2'=>'','line_3_position_3'=>'','line_3_position_4'=>'','line_3_position_5'=>''],
                        ['line_4_position_1'=>'','line_4_position_2'=>'','line_4_position_3'=>'','line_4_position_4'=>'','line_4_position_5'=>''],
                        ['line_5_position_1'=>'','line_5_position_2'=>'','line_5_position_3'=>'','line_5_position_4'=>'','line_5_position_5'=>''],
                    ],
                    [
                        ['line_1_position_1'=>'','line_1_position_2'=>'','line_1_position_3'=>'','line_1_position_4'=>'','line_1_position_5'=>''],
                        ['line_2_position_1'=>'','line_2_position_2'=>'','line_2_position_3'=>'','line_2_position_4'=>'','line_2_position_5'=>''],
                        ['line_3_position_1'=>'','line_3_position_2'=>'','line_3_position_3'=>'','line_3_position_4'=>'','line_3_position_5'=>''],
                        ['line_4_position_1'=>'','line_4_position_2'=>'','line_4_position_3'=>'','line_4_position_4'=>'','line_4_position_5'=>''],
                        ['line_5_position_1'=>'','line_5_position_2'=>'0','line_5_position_3'=>'0','line_5_position_4'=>'0','line_5_position_5'=>'0'],
                    
                    ]
                ])
            ],
            [
                'id' => 2,
                'chord_name' => "D",
                'chord_positions' => json_encode([[
                    ['line_1_position_1'=>'','line_1_position_2'=>'','line_1_position_3'=>'','line_1_position_4'=>'','line_1_position_5'=>''],
                    ['line_2_position_1'=>'','line_2_position_2'=>'','line_2_position_3'=>'1','line_2_position_4'=>'','line_2_position_5'=>''],
                    ['line_3_position_1'=>'','line_3_position_2'=>'','line_3_position_3'=>'','line_3_position_4'=>'2','line_3_position_5'=>''],
                    ['line_4_position_1'=>'','line_4_position_2'=>'4','line_4_position_3'=>'','line_4_position_4'=>'','line_4_position_5'=>'3'],
                    ['line_5_position_1'=>'','line_5_position_2'=>'','line_5_position_3'=>'','line_5_position_4'=>'','line_5_position_5'=>''],
                    
                    ]
                ])
            ],
            [
            'id' => 3,
            'chord_name' => "Am",
            'chord_positions' =>json_encode([[
                ['line_1_position_1'=>'','line_1_position_2'=>'','line_1_position_3'=>'','line_1_position_4'=>'1','line_1_position_5'=>''],
                ['line_2_position_1'=>'','line_2_position_2'=>'2','line_2_position_3'=>'3','line_2_position_4'=>'','line_2_position_5'=>'4'],
                ['line_3_position_1'=>'','line_3_position_2'=>'','line_3_position_3'=>'','line_3_position_4'=>'','line_3_position_5'=>''],
                ['line_4_position_1'=>'','line_4_position_2'=>'','line_4_position_3'=>'','line_4_position_4'=>'','line_4_position_5'=>''],
                ['line_5_position_1'=>'','line_5_position_2'=>'','line_5_position_3'=>'','line_5_position_4'=>'','line_5_position_5'=>''],
                
                ],
                [
                    ['line_1_position_1'=>'5°','line_1_position_2'=>'','line_1_position_3'=>'1','line_1_position_4'=>'2','line_1_position_5'=>''],
                    ['line_2_position_1'=>'','line_2_position_2'=>'','line_2_position_3'=>'','line_2_position_4'=>'','line_2_position_5'=>''],
                    ['line_3_position_1'=>'','line_3_position_2'=>'3','line_3_position_3'=>'','line_3_position_4'=>'','line_3_position_5'=>'4'],
                    ['line_4_position_1'=>'','line_4_position_2'=>'','line_4_position_3'=>'','line_4_position_4'=>'','line_4_position_5'=>''],
                    ['line_5_position_1'=>'','line_5_position_2'=>'','line_5_position_3'=>'','line_5_position_4'=>'','line_5_position_5'=>''],
                ]
            ])
            
            ]
        ]);
    }
}