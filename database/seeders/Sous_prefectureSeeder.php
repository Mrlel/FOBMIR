<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use League\Csv\Reader;
use Illuminate\Support\Facades\DB;

class Sous_prefectureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                // Charger le fichier CSV
                $csv = Reader::createFromPath(database_path('seeders/data/sous_prefectures.csv'), 'r');
                $csv->setDelimiter('|');
                $csv->setHeaderOffset(0);
        
                foreach ($csv as $record) {
                    $departement = DB::table('departements')->where('code', $record['iddep'])->first();
                    DB::table('sous_prefectures')->insert([
                        'code' => $record['idsp'],
                        'nom' => $record['nomspref'],
                        'superficie' => (int) $record['superfisp'],
                        'nb_villages' => (int) $record['nbrvilsp'],
                        'departement_id' => $departement->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

    }
}
