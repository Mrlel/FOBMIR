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
        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->string('code', 150);
            $table->string('chef_lieu', 150)->nullable();
            $table->integer('superficie')->nullable();
            $table->string('nom', 150);
            $table->integer('nb_villages')->nullable();
            $table->foreignId('pays_id')->constrained('pays')->cascadeOnDelete();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('districts');
    }
};
