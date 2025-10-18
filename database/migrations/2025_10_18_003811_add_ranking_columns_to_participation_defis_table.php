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
        Schema::table('participation_defis', function (Blueprint $table) {
            $table->decimal('average_score', 3, 2)->nullable()->after('quiz_completed_at');
            $table->integer('completion_time_minutes')->nullable()->after('average_score');
            $table->decimal('ranking_score', 5, 2)->nullable()->after('completion_time_minutes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participation_defis', function (Blueprint $table) {
            $table->dropColumn(['average_score', 'completion_time_minutes', 'ranking_score']);
        });
    }
};