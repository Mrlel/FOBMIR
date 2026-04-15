<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dossiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pochette_id')->nullable()->constrained('pochettes')->cascadeOnDelete();
            $table->foreignId('individu_menage_id')->nullable()->constrained('individu_menage')->cascadeOnDelete();
            $table->foreignId('individu_independant_id')->nullable()->constrained('individus_independants')->cascadeOnDelete();
            $table->string('nom', 200); // Nom du dossier (ex: "Dossier de Jean Dupont")
            $table->text('description')->nullable();
            $table->timestamps();

            // Un seul dossier par individu par pochette
            $table->unique(['pochette_id', 'individu_menage_id']);
            $table->unique(['pochette_id', 'individu_independant_id']);
            // Index pour les recherches
            $table->index(['pochette_id', 'individu_menage_id']);
            $table->index(['pochette_id', 'individu_independant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dossiers');
    }
};