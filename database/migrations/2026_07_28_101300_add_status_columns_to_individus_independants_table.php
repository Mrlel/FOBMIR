<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajoute les colonnes utilisées par le modèle IndividuIndependant
     * (AutoEnregistrementController::verifyEmail, marquerProfilComplet) mais
     * jamais créées par la migration initiale de la table.
     */
    public function up(): void
    {
        Schema::table('individus_independants', function (Blueprint $table) {
            $table->boolean('email_verifie')->default(false)->after('email_verified_at');
            $table->boolean('profil_complet')->default(false)->after('adresse_complete');
            $table->boolean('actif')->default(true)->after('profil_complet');
        });
    }

    public function down(): void
    {
        Schema::table('individus_independants', function (Blueprint $table) {
            $table->dropColumn(['email_verifie', 'profil_complet', 'actif']);
        });
    }
};
