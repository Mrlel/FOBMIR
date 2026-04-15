<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->string('nom_fichier', 150)->nullable()->after('numero');
            $table->foreignId('classeur_id')->nullable()->after('type_document_id')->constrained('classeurs')->nullOnDelete();
            $table->foreignId('menage_id')->nullable()->after('classeur_id')->constrained('menages')->nullOnDelete();
            $table->foreignId('individu_menage_id')->nullable()->after('menage_id')->constrained('individu_menage')->nullOnDelete();
            $table->timestamp('date_ajout')->nullable()->after('individu_menage_id');

            $table->index(['menage_id', 'individu_menage_id']);
            $table->index(['type_document_id', 'classeur_id']);
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropIndex(['menage_id', 'individu_menage_id']);
            $table->dropIndex(['type_document_id', 'classeur_id']);

            $table->dropConstrainedForeignId('individu_menage_id');
            $table->dropConstrainedForeignId('menage_id');
            $table->dropConstrainedForeignId('classeur_id');
            $table->dropColumn('nom_fichier');
            $table->dropColumn('date_ajout');
        });
    }
};
