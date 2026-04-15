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
        Schema::create('chefs_village', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 150);
            $table->enum('sexe', ['M', 'F']);
            $table->date('debut_mandat')->nullable();
            $table->date('fin_mandat')->nullable();
            $table->string('num_arrete_nomination', 25)->nullable();
            $table->foreignId('village_id')->constrained('villages')->cascadeOnDelete();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chef_villages');
    }
};
