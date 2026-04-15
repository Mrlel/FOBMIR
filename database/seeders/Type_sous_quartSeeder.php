<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Type_sous_quartSeeder extends Seeder
{
    /*
    COMPATIMENT DU CAMPEMENT
COMPATIMENT DU VILLAGE NOYAU
COMPATIMENT DU VILLAGE RATTACHE
COMPATIMENT QUARTIER URBAIN
COMPATIMENT SELON GROUPE ETHNIQUE
COMPATIMENT SELON GROUPE RELIGIEUX
CORPORATION DU CAMPEMENT
CORPORATION DU VILLAGE NOYAU
CORPORATION DU VILLAGE RATTACHE
CORPORATION MILIEU URBAIN
GRANDE FAMILLE DU VILLAGE NOYAU
GRANDEFAM ILLE DU VILLAGE RATTACHE
     */

     
    public function run(): void
    {
        DB::table('type_sous_quartiers')->insert([
            ['libelle'  => 'COMPATIMENT DU CAMPEMENT'],
            ['libelle'  => 'COMPATIMENT DU VILLAGE NOYAU'],
            ['libelle'  => 'COMPATIMENT DU VILLAGE RATTACHE'],
            ['libelle'  => 'COMPATIMENT QUARTIER URBAIN'],
            ['libelle'  => 'COMPATIMENT SELON GROUPE ETHNIQUE'],
            ['libelle'  => 'COMPATIMENT SELON GROUPE RELIGIEUX'],
            ['libelle'  => 'CORPORATION DU CAMPEMENT'],
            ['libelle'  => 'CORPORATION DU VILLAGE NOYAU'],
            ['libelle'  => 'CORPORATION DU VILLAGE RATTACHE'],
            ['libelle'  => 'CORPORATION MILIEU URBAIN'],
            ['libelle'  => 'GRANDE FAMILLE DU VILLAGE NOYAU'],
            ['libelle'  => 'GRANDEFAM ILLE DU VILLAGE RATTACHE'],
        ]);
    }
}
