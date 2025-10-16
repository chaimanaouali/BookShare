<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, add new columns to livres table to accommodate LivreUtilisateur fields
        Schema::table('livres', function (Blueprint $table) {
            // Add user-specific fields from LivreUtilisateur
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('bibliotheque_id')->nullable()->constrained('bibliotheque_virtuelles')->onDelete('cascade');
            $table->string('fichier_livre')->nullable();
            $table->string('format')->nullable();
            $table->string('taille')->nullable();
            $table->enum('visibilite', ['public', 'private'])->default('private');
            $table->text('user_description')->nullable(); // Renamed to avoid conflict with existing description
            
            // Add additional fields that might be needed
            $table->string('langue')->nullable();
            $table->integer('nb_pages')->nullable();
            $table->text('resume')->nullable();
            $table->boolean('disponibilite')->default(true);
            $table->string('etat')->nullable(); // e.g., 'neuf', 'bon', 'usé'
        });

        // Migrate data from livre_utilisateurs to livres
        $this->migrateLivreUtilisateurData();

        // Update foreign key constraints in related tables
        $this->updateRelatedTables();

        // Drop the livre_utilisateurs table
        Schema::dropIfExists('livre_utilisateurs');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate livre_utilisateurs table
        Schema::create('livre_utilisateurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('bibliotheque_id')->constrained('bibliotheque_virtuelles')->onDelete('cascade');
            $table->foreignId('livre_id')->constrained('livres')->onDelete('cascade');
            $table->string('fichier_livre');
            $table->string('format')->nullable();
            $table->string('taille')->nullable();
            $table->enum('visibilite', ['public', 'private'])->default('private');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Remove the added columns from livres table
        Schema::table('livres', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['bibliotheque_id']);
            $table->dropColumn([
                'user_id', 'bibliotheque_id', 'fichier_livre', 'format', 
                'taille', 'visibilite', 'user_description', 'langue', 
                'nb_pages', 'resume', 'disponibilite', 'etat'
            ]);
        });

        // Restore foreign key constraints in related tables
        $this->restoreRelatedTables();
    }

    /**
     * Migrate data from livre_utilisateurs to livres
     */
    private function migrateLivreUtilisateurData(): void
    {
        // Get all livre_utilisateurs data
        $livreUtilisateurs = DB::table('livre_utilisateurs')->get();

        foreach ($livreUtilisateurs as $lu) {
            // Update the corresponding livre record
            DB::table('livres')
                ->where('id', $lu->livre_id)
                ->update([
                    'user_id' => $lu->user_id,
                    'bibliotheque_id' => $lu->bibliotheque_id,
                    'fichier_livre' => $lu->fichier_livre,
                    'format' => $lu->format,
                    'taille' => $lu->taille,
                    'visibilite' => $lu->visibilite,
                    'user_description' => $lu->description,
                ]);
        }
    }

    /**
     * Update foreign key constraints in related tables
     */
    private function updateRelatedTables(): void
    {
        // Update avis table to reference livres directly instead of through livre_utilisateurs
        // This assumes avis currently references livre_utilisateurs
        // If avis already references livres, this might not be needed
        
        // Update emprunts table if it references livre_utilisateurs
        // This would need to be adjusted based on your current schema
    }

    /**
     * Restore foreign key constraints in related tables
     */
    private function restoreRelatedTables(): void
    {
        // Restore any foreign key constraints that were modified
        // This would be the reverse of updateRelatedTables()
    }
};