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
        Schema::create('recommendations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('livre_id');
            $table->unsignedBigInteger('avis_id')->nullable();
            $table->float('score');
            $table->date('date_creation');
            $table->string('source'); // AI, collaborative, manual
            $table->text('reason')->nullable(); // Reason for recommendation
            $table->boolean('is_viewed')->default(false);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('livre_id')->references('id')->on('livres')->onDelete('cascade');
            $table->foreign('avis_id')->references('id')->on('avis')->onDelete('set null');

            // Indexes for better performance
            $table->index(['user_id', 'date_creation']);
            $table->index(['livre_id']);
            $table->index(['source']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommendations');
    }
};
