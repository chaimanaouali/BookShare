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
        Schema::table('livres', function (Blueprint $table) {
            $table->foreignId('categorie_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->index(['categorie_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('livres', function (Blueprint $table) {
            $table->dropForeign(['categorie_id']);
            $table->dropIndex(['categorie_id']);
            $table->dropColumn('categorie_id');
        });
    }
};
