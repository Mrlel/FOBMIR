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
        Schema::create('menages', function (Blueprint $table) {
            $table->id();
            $table->string('nom_chef', 150);
            $table->integer('nb_individus')->nullable();
            $table->integer('nb_indiv_prst')->nullable();
            $table->integer('nb_indiv_abs')->nullable();
            $table->enum('sexe_chef', ['M', 'F'])->nullable();
            $table->foreignId('origine_id')->nullable()->constrained('origine_menages')->cascadeOnDelete();
            $table->foreignId('sous_quartier_id')->constrained('sous_quartiers')->cascadeOnDelete();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menages');
    }
};
