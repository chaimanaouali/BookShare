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
        Schema::create('reading_personalities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('personality_title'); // e.g., "Explorateur curieux"
            $table->text('personality_description'); // Main description
            $table->json('reading_patterns'); // JSON with patterns like genres, themes, etc.
            $table->json('recommendations'); // Suggested next reads
            $table->text('challenge_suggestion'); // Next reading challenge
            $table->integer('books_analyzed')->default(0);
            $table->timestamp('last_updated')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'last_updated']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reading_personalities');
    }
};
