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
        Schema::create('individus_independants', function (Blueprint $table) {
            $table->id();
            
            // Informations personnelles
            $table->string('prenom');
            $table->string('nom');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('telephone');
            $table->date('date_naissance')->nullable();
            $table->enum('sexe', ['M', 'F']);
            $table->string('profession')->nullable();
            $table->text('adresse_complete')->nullable();
            $table->timestamp('derniere_connexion')->nullable();
            
            // Géolocalisation carte 
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);

            // Tokens
            $table->string('remember_token')->nullable();
            $table->string('verification_token')->nullable();
            
            $table->timestamps();
  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('individus_independants');
    }
};