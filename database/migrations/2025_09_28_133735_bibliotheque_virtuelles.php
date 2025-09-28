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
        Schema::create('bibliotheque_virtuelles', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')
            ->constrained('users')
            ->onDelete('cascade');
        $table->string('nom_bibliotheque');
        $table->integer('nb_livres')->default(0);
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
