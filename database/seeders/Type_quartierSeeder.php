<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Type_quartierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('type_quartiers')->insert([
            ['libelle'  => 'CAMPEMENT VILLAGE NOYAU'],
            ['libelle'  => 'CAMPEMENT VILLAGE RATTACHE'],
            ['libelle'  => 'VILLAGE RATTACHE'],
            ['libelle'  => 'VILLAGE NOYAU'],
            ['libelle'  => 'QUARTIER URBAIN',
            ],
        ]);
    }
}
