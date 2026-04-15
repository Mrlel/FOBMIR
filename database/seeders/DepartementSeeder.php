<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use League\Csv\Reader;
use Illuminate\Support\Facades\DB;

class DepartementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csv = Reader::createFromPath(database_path('seeders/data/departements.csv'), 'r');
        $csv->setDelimiter(';');
        $csv->setHeaderOffset(0); // première ligne = en-têtes
        
        foreach ($csv as $record) {
            $region = DB::table('regions')->where('code', $record['idreg'])->first();

            DB::table('departements')->insert([
                'code' => $record['iddep'],
                'nom' => $record['nomdep'],
                'superficie' => (int) $record['superfidep'],
                'nb_villages' => (int) $record['nbrvildep'],
                'region_id' => $region->id, // ID auto-incrémenté
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
