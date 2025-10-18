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
            $table->integer('quiz_score')->nullable()->after('note');
            $table->integer('quiz_total_questions')->nullable()->after('quiz_score');
            $table->timestamp('quiz_completed_at')->nullable()->after('quiz_total_questions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participation_defis', function (Blueprint $table) {
            $table->dropColumn(['quiz_score', 'quiz_total_questions', 'quiz_completed_at']);
        });
    }
};