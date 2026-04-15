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

            $table->foreignId('document_id')->constrained('documents')->cascadeOnDelete();
            $table->morphs('payer');

            $table->string('provider', 30)->default('cinetpay');
            $table->string('status', 20)->default('pending'); // pending|paid|failure
            $table->unsignedInteger('amount');

            $table->string('token', 100)->nullable()->unique();
            $table->text('payment_url')->nullable();

            $table->string('numero_send', 30)->nullable();
            $table->string('nomclient', 150)->nullable();

            $table->json('provider_payload')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();

            $table->index(['document_id', 'payer_type', 'payer_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_payments');
    }
};

