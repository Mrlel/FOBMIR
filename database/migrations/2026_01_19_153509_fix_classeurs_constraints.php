<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ajouter la colonne dossier_id si elle n'existe pas
        if (!Schema::hasColumn('classeurs', 'dossier_id')) {
            Schema::table('classeurs', function (Blueprint $table) {
                $table->foreignId('dossier_id')->nullable()->after('pochette_id')->constrained('dossiers')->nullOnDelete();
            });
        }
        
        // Supprimer l'ancienne contrainte unique si elle existe encore
        try {
            DB::statement('ALTER TABLE classeurs DROP INDEX classeurs_pochette_id_theme_unique');
        } catch (Exception $e) {
            // Ignore si la contrainte n'existe pas
        }
        
        // Ajouter les contraintes et index nécessaires
        try {
            // Contrainte pour s'assurer qu'un classeur appartient soit à une pochette directement, soit à un dossier
            DB::statement('ALTER TABLE classeurs ADD CONSTRAINT check_classeur_context CHECK (
                (pochette_id IS NOT NULL AND dossier_id IS NULL) OR 
                (dossier_id IS NOT NULL)
            )');
        } catch (Exception $e) {
            // Ignore si la contrainte existe déjà
        }
        
        try {
            // Index pour les recherches
            Schema::table('classeurs', function (Blueprint $table) {
                $table->index(['pochette_id', 'dossier_id', 'theme']);
            });
        } catch (Exception $e) {
            // Ignore si l'index existe déjà
        }
        
        try {
            // Nouvelle contrainte unique conditionnelle
            DB::statement('CREATE UNIQUE INDEX classeurs_unique_theme ON classeurs (
                COALESCE(pochette_id, 0), 
                COALESCE(dossier_id, 0), 
                theme
            )');
        } catch (Exception $e) {
            // Ignore si l'index existe déjà
        }
    }

    public function down(): void
    {
        try {
            DB::statement('DROP INDEX classeurs_unique_theme ON classeurs');
        } catch (Exception $e) {
            // Ignore
        }
        
        try {
            DB::statement('ALTER TABLE classeurs DROP CONSTRAINT check_classeur_context');
        } catch (Exception $e) {
            // Ignore
        }
        
        try {
            Schema::table('classeurs', function (Blueprint $table) {
                $table->dropIndex(['pochette_id', 'dossier_id', 'theme']);
            });
        } catch (Exception $e) {
            // Ignore
        }
    }
};