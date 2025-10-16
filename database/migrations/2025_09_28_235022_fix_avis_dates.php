<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix any reviews with future dates to current system date
        $currentDate = now()->toDateString();
        DB::table('avis')
            ->where('date_publication', '>=', '2025-01-01')
            ->update(['date_publication' => $currentDate]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this migration
    }
};