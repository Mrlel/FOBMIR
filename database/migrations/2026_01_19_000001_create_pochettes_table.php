<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pochettes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menage_id')->constrained('menages')->cascadeOnDelete();
            $table->string('libelle', 150)->default('Pochette du ménage');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique('menage_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pochettes');
    }
};
