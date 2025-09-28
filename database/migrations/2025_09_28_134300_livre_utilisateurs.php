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
        Schema::create('livre_utilisateurs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')
            ->constrained('users')
            ->onDelete('cascade');
        $table->foreignId('bibliotheque_id')
            ->constrained('bibliotheque_virtuelles')
            ->onDelete('cascade');
        $table->foreignId('livre_id')
            ->constrained('livres')
            ->onDelete('cascade'); 
        $table->string('fichier_livre');
        $table->string('format')->nullable();
        $table->string('taille')->nullable();
        $table->enum('visibilite', ['public', 'private'])->default('private');
        $table->text('description')->nullable();
        $table->timestamps();
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
