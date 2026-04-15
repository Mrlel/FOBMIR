<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use League\Csv\Reader;
use Illuminate\Support\Facades\DB;

class QuartierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csv = Reader::createFromPath(database_path('seeders/data/quartiers.csv'), 'r');
        $csv->setDelimiter(',');
        $csv->setHeaderOffset(0);

        foreach ($csv as $record) {
            $village = DB::table('villages')->where('code', $record['idvillage'])->first();
            DB::table('quartiers')->insert([
                'code' => $record['idquart'], 
                'nom' => $record['nomquart'],
                'village_id' => $village?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
        }
    }
}
