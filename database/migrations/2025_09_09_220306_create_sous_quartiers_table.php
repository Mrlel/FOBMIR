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
        Schema::create('sous_quartiers', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 150);
            $table->text('historique')->nullable();
            $table->foreignId('quartier_id')->constrained('quartiers')->cascadeOnDelete();
            $table->foreignId('type_sous_quartier_id')->nullable()->constrained('type_sous_quartiers')->cascadeOnDelete();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sous_quartiers');
    }
};
