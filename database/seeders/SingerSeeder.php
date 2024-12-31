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
                'singer_name' => 'Reinado',
                'singer_type' => false,
            ],
            [
                'singer_name' => 'Sem Compromisso',
                'singer_type' => true,
            ],
        ]);
    }
}
