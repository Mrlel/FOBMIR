<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use League\Csv\Reader;
use Illuminate\Support\Facades\DB;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Charger le fichier CSV
        $csv = Reader::createFromPath(database_path('seeders/data/regions.csv'), 'r');
        $csv->setDelimiter(';');
        $csv->setHeaderOffset(0); // première ligne = en-têtes

        foreach ($csv as $record) {
            $district = DB::table('districts')->where('code', $record['iddist'])->first();
            DB::table('regions')->insert([
                'code' => $record['idreg'], // CIV0301
                'nom' => $record['nomreg'],
                'chef_lieu' => $record['cheflieureg'],
                'superficie' => (int) $record['superfireg'],
                'nb_villages' => (int) $record['nbrvilreg'],
                'district_id' => $district?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
        }
    }
}
