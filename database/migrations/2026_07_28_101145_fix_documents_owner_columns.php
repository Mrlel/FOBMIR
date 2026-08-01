<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Aligne la table "documents" sur ce qu'utilisent réellement le modèle Document
     * et les contrôleurs : ajoute "user_id" (auteur du dépôt) et
     * "individu_independant_id" (document d'un individu indépendant),
     * et supprime la colonne "individu_id" orpheline jamais utilisée par le code.
     */
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('type_document_id')
                ->constrained('users')->nullOnDelete();

            $table->foreignId('individu_independant_id')->nullable()->after('individu_menage_id')
                ->constrained('individus_independants')->nullOnDelete();
        });

        if (Schema::hasColumn('documents', 'individu_id')) {
            Schema::table('documents', function (Blueprint $table) {
                $table->dropConstrainedForeignId('individu_id');
            });
        }
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropConstrainedForeignId('individu_independant_id');

            $table->foreignId('individu_id')->nullable()->constrained('individus_independants')->cascadeOnDelete();
        });
    }
};
