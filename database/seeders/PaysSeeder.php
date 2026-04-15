<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class PaysSeeder extends Seeder
{
    public function run(): void
    {
        // Charger le fichier CSV avec séparateur ";"
        $csv = Reader::createFromPath(database_path('seeders/data/pays.csv'), 'r');
        $csv->setDelimiter(';');
        $csv->setHeaderOffset(0); // première ligne = entêtes

        foreach ($csv as $record) {
            DB::table('pays')->insert([
                // ⚠️ On ignore la première colonne "id" du CSV
                'code' => $record['code'] ?? null,
                'nom' => $record['nompay'] ?? null,
                'capitale_politique' => $record['capipolipay'] ?? null,
                'capitale_economique' => $record['capiecopay'] ?? null,
                'superficie' => is_numeric($record['superficipay']) ? (int) $record['superficipay'] : null,
                'continent' => $record['Contipay'] ?? $record['contipay'] ?? null,
                'indicatif_tel' => $record['indictelpay'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
