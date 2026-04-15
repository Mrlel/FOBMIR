<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menage;
use App\Models\Pochette;
use App\Models\Classeur;

class ClasseurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer des pochettes et classeurs pour les ménages existants
        $menages = Menage::all();
        
        $classeursParDefaut = [
            'État civil' => 'Documents d\'état civil (actes de naissance, mariage, décès...)',
            'Identité' => 'Pièces d\'identité (CNI, passeport, permis...)',
            'Santé' => 'Documents de santé (carnets de vaccination, certificats médicaux...)',
            'Scolarité' => 'Documents scolaires (diplômes, bulletins, attestations...)',
        ];

        foreach ($menages as $menage) {
            // Créer la pochette si elle n'existe pas
            $pochette = $menage->pochette;
            if (!$pochette) {
                $pochette = Pochette::create([
                    'menage_id' => $menage->id,
                    'libelle' => 'Pochette de ' . $menage->nom_chef,
                    'description' => 'Pochette automatiquement créée pour le ménage de ' . $menage->nom_chef
                ]);
            }

            // Créer les classeurs par défaut
            foreach ($classeursParDefaut as $theme => $description) {
                Classeur::firstOrCreate([
                    'pochette_id' => $pochette->id,
                    'theme' => $theme,
                ], [
                    'description' => $description,
                ]);
            }
        }
    }
}