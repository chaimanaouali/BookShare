<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('livres', function (Blueprint $table) {
        // Rename old columns if needed
        if (Schema::hasColumn('livres', 'titre')) {
            $table->renameColumn('titre', 'title');
        }
        if (Schema::hasColumn('livres', 'auteur')) {
            $table->renameColumn('auteur', 'author');
        }
        if (Schema::hasColumn('livres', 'annee_publication')) {
            $table->renameColumn('annee_publication', 'publication_date');
        }
        // Add new columns if they don't exist
        if (!Schema::hasColumn('livres', 'description')) {
            $table->text('description')->nullable();
        }
        if (!Schema::hasColumn('livres', 'cover_image')) {
            $table->string('cover_image')->nullable();
        }
        if (!Schema::hasColumn('livres', 'genre')) {
            $table->string('genre')->nullable();
        }
        // Remove columns not in the model
        if (Schema::hasColumn('livres', 'editeur')) {
            $table->dropColumn('editeur');
        }
    });
}

public function down()
{
    Schema::table('livres', function (Blueprint $table) {
        // Reverse the changes if needed
        $table->renameColumn('title', 'titre');
        $table->renameColumn('author', 'auteur');
        $table->renameColumn('publication_date', 'annee_publication');
        $table->dropColumn(['description', 'cover_image', 'genre']);
        $table->string('editeur')->nullable();
    });
}
};
