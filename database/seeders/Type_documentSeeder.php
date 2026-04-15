<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Type_documentSeeder extends Seeder
{
    /*
    EXTRAIT D'ACTE DE NAISSANCE
EXTRAIT D'ACTE DE DECES
EXTRAIT D'ACTE DE MARIAGE
EXTRAIT D'ACTE DE DIVORCE
CARTE NATIONALE D'IDENTITE
PASSEPORT
DIPLOME
DOCUMENT COMMERCIAL
DOCUMENT ADMINISTRATIF
     */
    public function run(): void
    {
        DB::table('type_documents')->insert([
            ['libelle'  => 'EXTRAIT D\'ACTE DE NAISSANCE'],
            ['libelle'  => 'EXTRAIT D\'ACTE DE DECES'],
            ['libelle'  => 'EXTRAIT D\'ACTE DE MARIAGE'],
            ['libelle'  => 'EXTRAIT D\'ACTE DE DIVORCE'],
            ['libelle'  => 'CARTE NATIONALE D\'IDENTITE'],
            ['libelle'  => 'PASSEPORT'],
            ['libelle'  => 'DIPLOME'],
            ['libelle'  => 'DOCUMENT COMMERCIAL'],
            ['libelle'  => 'DOCUMENT ADMINISTRATIF'],
        ]);
    }
}
