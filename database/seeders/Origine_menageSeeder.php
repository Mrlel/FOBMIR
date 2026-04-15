<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Origine_menageSeeder extends Seeder
{
    /*
    AUTOCHTONE
ALLOCHTONE
RESSORTISSANT CDEAO
RESSORTISSANT AFRICAIN
RESSORTISSANT ASIATIQUE
RESSORTISSANT EUROPEEN
RESSORTISSANT AMERIOQUE DU SUD
RESSORTISSANT AMERIOQUE DU NORD
RESSORTISSANT AMERIOQUE DU CENTRE
RESSORTISSANT DE L'UNION EUROPEENNE
RESSORTISSANT DE L'AUTRE EUROPE
     */
    public function run(): void
    {
        DB::table('origine_menages')->insert([
            ['libelle'  => 'AUTOCHTONE'],
            ['libelle'  => 'ALLOCHTONE'],
            ['libelle'  => 'RESSORTISSANT CDEAO'],
            ['libelle'  => 'RESSORTISSANT AFRICAIN'],
            ['libelle'  => 'RESSORTISSANT ASIATIQUE'],
            ['libelle'  => 'RESSORTISSANT EUROPEEN'],
            ['libelle'  => 'RESSORTISSANT AMERIOQUE DU SUD'],
            ['libelle'  => 'RESSORTISSANT AMERIOQUE DU NORD'],
            ['libelle'  => 'RESSORTISSANT AMERIOQUE DU CENTRE'],
            ['libelle'  => 'RESSORTISSANT DE L\'UNION EUROPEENNE'],
            ['libelle'  => 'RESSORTISSANT DE L\'AUTRE EUROPE'],
        ]);
    }
}
