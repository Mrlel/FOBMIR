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
        Schema::create('villages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sous_prefecture_id')->constrained('sous_prefectures')->cascadeOnDelete();
            $table->foreignId('commune_id')->nullable()->constrained('communes')->cascadeOnDelete();
            $table->string('code', 150);
            $table->string('nom', 150);
            $table->string('secteur_communal', 50)->nullable();
            $table->string('num_arrete_creation', 150)->nullable();
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('villages');
    }
};
