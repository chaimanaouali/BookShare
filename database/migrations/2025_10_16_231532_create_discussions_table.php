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
        Schema::create('discussions', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('contenu');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('bibliotheque_id')->constrained('bibliotheque_virtuelles')->onDelete('cascade');
            $table->boolean('est_resolu')->default(false);
            $table->timestamps();
            
            // Add indexes for better performance
            $table->index(['user_id']);
            $table->index(['bibliotheque_id']);
            $table->index(['est_resolu']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discussions');
    }
};
