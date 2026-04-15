<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use League\Csv\Reader;
use Illuminate\Support\Facades\DB;

class VillageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $csv = Reader::createFromPath(database_path('seeders/data/villages.csv'), 'r');
        $csv->setDelimiter(',');
        $csv->setHeaderOffset(0);

        $total = 0;
        $success = 0;
        $missingCommune = 0;
        $missingSousPref = 0;
        $secteurNonCommunal = 0;
        $problematicRecords = [];

        foreach ($csv as $index => $record) {
            $total++;
            
            // Vérifier si c'est un secteur non communal
            $isSecteurNonCommunal = trim($record['secteur_com']) === 'Secteur non communal';
            
            $commune = null;
            $commune_id = null;
            
            if (!$isSecteurNonCommunal) {
                // Pour les secteurs communaux, la commune est obligatoire
                $commune = DB::table('communes')->where('code', $record['idcom'])->first();
                if (!$commune) {
                    $message = "Ligne {$index}: Commune non trouvée pour le code: " . $record['idcom'] . " (village: " . $record['nomvill'] . ")";
                    $this->command->warn($message);
                    $problematicRecords[] = $message;
                    $missingCommune++;
                    continue;
                }
                $commune_id = $commune->id;
            } else {
                // Pour les secteurs non communaux, commune_id peut être null
                $secteurNonCommunal++;
            }

            $sous_prefecture = DB::table('sous_prefectures')->where('code', $record['idsp'])->first();
            if (!$sous_prefecture) {
                $message = "Ligne {$index}: Sous-préfecture non trouvée pour le code: " . $record['idsp'] . " (village: " . $record['nomvill'] . ")";
                $this->command->warn($message);
                $problematicRecords[] = $message;
                $missingSousPref++;
                continue;
            }

            try {
                DB::table('villages')->insert([
                    'code' => $record['idvillage'], 
                    'nom' => $record['nomvill'],
                    'secteur_communal' => $record['secteur_com'],
                    'num_arrete_creation' => null,
                    'commune_id' => $commune_id,
                    'sous_prefecture_id' => $sous_prefecture->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $success++;
            } catch (\Exception $e) {
                $message = "Ligne {$index}: Erreur lors de l'insertion du village " . $record['nomvill'] . ": " . $e->getMessage();
                $this->command->error($message);
                $problematicRecords[] = $message;
            }
        }

        // Afficher un résumé
        $this->command->info("\nRésumé de l'importation :");
        $this->command->info("- Total des enregistrements traités : " . $total);
        $this->command->info("- Villages insérés avec succès : " . $success);
        $this->command->info("- Villages avec secteur non communal (sans commune) : " . $secteurNonCommunal);
        $this->command->warn("- Communes non trouvées : " . $missingCommune);
        $this->command->warn("- Sous-préfectures non trouvées : " . $missingSousPref);
        
        if (!empty($problematicRecords)) {
            $this->command->info("\nDétails des problèmes :");
            foreach (array_slice($problematicRecords, 0, 10) as $problem) {
                $this->command->line("- " . $problem);
            }
            if (count($problematicRecords) > 10) {
                $this->command->line("- ... et " . (count($problematicRecords) - 10) . " problèmes supplémentaires");
            }
        }
    }
}
