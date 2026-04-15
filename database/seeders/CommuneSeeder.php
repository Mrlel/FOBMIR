<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use League\Csv\Reader;
use Illuminate\Support\Facades\DB;

class CommuneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csv = Reader::createFromPath(database_path('seeders/data/communes.csv'), 'r');
        $csv->setDelimiter(';');
        $csv->setHeaderOffset(0); // première ligne = en-têtes

        foreach ($csv as $record) {
            $sous_prefecture = DB::table('sous_prefectures')->where('code', $record['idsp'])->first();
            DB::table('communes')->insert([
                'code' => $record['idcom'], 
                'nom' => $record['nomcom'],
                'sous_prefecture_id' => $sous_prefecture?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
        }
    }
}
