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
    Schema::create('book_events', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('defi_id'); // clé étrangère possible
        $table->string('type');
        $table->string('titre');
        $table->text('description')->nullable();
        $table->date('date_evenement');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_events');
    }
};
