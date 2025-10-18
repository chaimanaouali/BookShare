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
        Schema::create('participation_defis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('defi_id');
            $table->unsignedBigInteger('livre_id');
            $table->enum('status', ['en_cours', 'termine', 'abandonne'])->default('en_cours');
            $table->text('commentaire')->nullable();
            $table->integer('note')->nullable(); // Note de 1 à 5
            $table->timestamp('date_debut_lecture')->nullable();
            $table->timestamp('date_fin_lecture')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('defi_id')->references('id')->on('defis')->onDelete('cascade');
            $table->foreign('livre_id')->references('id')->on('livres')->onDelete('cascade');
            
            // Empêcher les doublons : un utilisateur ne peut participer qu'une fois au même défi avec le même livre
            $table->unique(['user_id', 'defi_id', 'livre_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participation_defis');
    }
};
