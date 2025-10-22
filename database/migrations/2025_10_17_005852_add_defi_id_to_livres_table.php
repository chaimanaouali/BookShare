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
            if (!Schema::hasColumn('livres', 'defi_id')) {
                $table->unsignedBigInteger('defi_id')->nullable()->after('categorie_id');
            }
        });
        
        // Add foreign key only if it doesn't exist
        if (!Schema::hasColumn('livres', 'defi_id')) {
            Schema::table('livres', function (Blueprint $table) {
                $table->foreign('defi_id')->references('id')->on('defis')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('livres', function (Blueprint $table) {
            $table->dropForeign(['defi_id']);
            $table->dropColumn('defi_id');
        });
    }
};
