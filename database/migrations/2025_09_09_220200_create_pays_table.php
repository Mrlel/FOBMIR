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
        Schema::create('pays', function (Blueprint $table) {
            $table->id();
            $table->string('code', 150);
            $table->string('nom');
            $table->string('capitale_politique', 150)->nullable();
            $table->string('capitale_economique', 150)->nullable();
            $table->integer('superficie')->nullable();
            $table->string('continent', 150);
            $table->string('indicatif_tel', 5)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pays');
    }
};
