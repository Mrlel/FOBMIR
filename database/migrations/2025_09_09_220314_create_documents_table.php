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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('libelle', 150);
            $table->string('numero', 25)->nullable();
            $table->string('fichier')->nullable();
             $table->boolean('est_valider')->default(false);
             $table->text('remarque')->nullable();
            $table->foreignId('type_document_id')->constrained('type_documents')->cascadeOnDelete();
            $table->foreignId('individu_id')->nullable()->constrained('individus_independants')->cascadeOnDelete();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
