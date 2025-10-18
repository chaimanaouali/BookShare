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
        Schema::table('book_events', function (Blueprint $table) {
            $table->enum('status', ['a_venir', 'en_cours', 'termine'])->default('a_venir')->after('date_evenement');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('book_events', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
