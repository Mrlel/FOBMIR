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
        Schema::table('individu_menage', function (Blueprint $table) {
            $table->foreignId('point_focal_id')->nullable()->after('menage_id')->constrained('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('individu_menage', function (Blueprint $table) {
            $table->dropForeign(['point_focal_id']);
            $table->dropColumn('point_focal_id');
        });
    }
};
