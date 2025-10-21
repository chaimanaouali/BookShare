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
        Schema::table('bibliotheque_virtuelles', function (Blueprint $table) {
            // Add description column
            $table->text('description')->nullable()->after('nom_bibliotheque');
            
            // Add soft delete column
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bibliotheque_virtuelles', function (Blueprint $table) {
            // Remove description column
            $table->dropColumn('description');
            
            // Remove soft delete column
            $table->dropSoftDeletes();
        });
    }
};