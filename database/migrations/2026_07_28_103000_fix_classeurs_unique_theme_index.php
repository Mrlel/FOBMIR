<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * La migration 2026_01_19_153509_fix_classeurs_constraints.php tentait de créer
     * cet index unique fonctionnel avec une syntaxe MySQL invalide (parenthèses
     * manquantes autour de chaque expression), erreur silencieusement avalée par
     * un catch(Exception) générique : l'index n'a en réalité jamais été créé et la
     * règle métier "un thème de classeur unique par pochette/dossier" n'est pas
     * appliquée en base. Cette migration la recrée avec la syntaxe correcte.
     */
    public function up(): void
    {
        $exists = DB::selectOne(
            "SELECT COUNT(*) AS n FROM information_schema.statistics
             WHERE table_schema = DATABASE() AND table_name = 'classeurs' AND index_name = 'classeurs_unique_theme'"
        );

        if ($exists->n > 0) {
            return;
        }

        try {
            DB::statement('CREATE UNIQUE INDEX classeurs_unique_theme ON classeurs (
                (COALESCE(pochette_id, 0)),
                (COALESCE(dossier_id, 0)),
                theme
            )');
        } catch (\Throwable $e) {
            // On journalise l'erreur réelle au lieu de l'avaler silencieusement :
            // si des doublons existent déjà en base, il faut les nettoyer manuellement
            // avant de pouvoir créer cette contrainte.
            Log::error('Impossible de créer classeurs_unique_theme : ' . $e->getMessage());
            throw $e;
        }
    }

    public function down(): void
    {
        try {
            DB::statement('DROP INDEX classeurs_unique_theme ON classeurs');
        } catch (\Throwable $e) {
            // Ignore si l'index n'existe pas
        }
    }
};
