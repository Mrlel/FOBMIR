<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classeurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pochette_id')->nullable()->constrained('pochettes')->cascadeOnDelete();
    
            $table->string('theme', 100);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['pochette_id', 'theme']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classeurs');
    }
};
