<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RhythmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rhythms')->insert([
            [
                'rhythm' => 'Partido alto',
                'time' => 4,
            ],
            [
                'rhythm' => 'Samba Enredo',
                'time' => 5,
            ],
        ]);
    }
}
