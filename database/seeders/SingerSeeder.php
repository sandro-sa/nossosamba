<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SingerSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('singers')->insert([
            [
                'singer_name' => 'REINALDO',
                'image' => 'image\singers\processed_1743348757.jpg',
                'singer_type' => false,
            ],
            [
                'singer_name' => 'Sem Compromisso',
                'image' => 'image\singers\image_default.jpg',
                'singer_type' => true,
            ],
        ]);
    }
}
