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
        Schema::create('avis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('livre_id')->constrained('livres')->onDelete('cascade');
            $table->integer('note')->unsigned()->between(1, 5);
            $table->text('commentaire')->nullable();
            $table->date('date_publication')->default(now());
            $table->timestamps();
            
            // Ensure one review per user per book
            $table->unique(['user_id', 'livre_id']);
            
            // Add indexes for better performance
            $table->index(['user_id']);
            $table->index(['livre_id']);
            $table->index(['note']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avis');
    }
};
