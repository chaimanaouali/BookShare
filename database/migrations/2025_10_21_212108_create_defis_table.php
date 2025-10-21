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
        Schema::create('defis', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('description');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->integer('nombre_livres_requis')->default(1);
            $table->string('type_defi')->default('lecture'); // lecture, quiz, etc.
            $table->boolean('actif')->default(true);
            $table->json('recompenses')->nullable(); // JSON pour stocker les récompenses
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('defis');
    }
};
