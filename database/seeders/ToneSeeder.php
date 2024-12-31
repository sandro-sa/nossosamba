<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ToneSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tones')->insert([
            [
                'tone' => 'C',
                
            ],
            [
                'tone' => 'Cm',
                
            ],
        ]);
    }
}
