<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents')->onDelete('cascade');
            $table->string('email_acheteur');
            $table->string('telephone_acheteur')->nullable();
            $table->string('nom_acheteur')->nullable();
            $table->enum('statut', ['en_attente', 'paye', 'echoue'])->default('en_attente');
            $table->string('transaction_id')->nullable();
            $table->string('token_telechargement')->unique()->nullable();
            $table->timestamp('paye_le')->nullable();
            $table->timestamps();
            
            $table->index(['document_id', 'email_acheteur', 'statut']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_payments');
    }
};

