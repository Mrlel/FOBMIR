<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class DistrictSeeder extends Seeder
{
    public function run(): void
    {
        // Charger le fichier CSV
        $csv = Reader::createFromPath(database_path('seeders/data/districts.csv'), 'r');
        $csv->setDelimiter(';');
        $csv->setHeaderOffset(0); // première ligne = en-têtes

        foreach ($csv as $record) {
            DB::table('districts')->insert([
                'code'        => $record['iddist'] ?? null,
                'chef_lieu'   => $record['cheflieudist'] ?? null,
                'superficie'  => is_numeric($record['superfidist']) ? (int) $record['superfidist'] : null,
                'nom'         => $record['nomdist'] ?? null,
                'pays_id'     => 48,
                'nb_villages' => is_numeric($record['nbrvilldist']) ? (int) $record['nbrvilldist'] : null,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}
