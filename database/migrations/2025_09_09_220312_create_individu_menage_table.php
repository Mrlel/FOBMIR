<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('individu_menage', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->string('prenom', 150);
            $table->string('telephone')->nullable();
            $table->date('date_naissance');
            $table->string('lieu_naissance');
            $table->string('numpiece', 25);
            $table->string('num_extrait_naissance', 25);
            $table->string('emploi', 150)->nullable();
            $table->string('doc_piece')->nullable();
            $table->string('nom_piece')->nullable();
            $table->foreignId('menage_id')->nullable()->constrained('menages')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('individu_menage');
    }
};
