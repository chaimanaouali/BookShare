<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HistoriqueEmprunt;
use App\Models\Emprunt;
use App\Models\User;
use Carbon\Carbon;

class HistoriqueEmpruntSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all completed emprunts
        $emprunts = Emprunt::whereNotNull('date_retour_eff')->get();
        
        foreach ($emprunts as $emprunt) {
            // Create historique entries for each emprunt
            
            // 1. Emprunt action (when book was borrowed)
            HistoriqueEmprunt::create([
                'emprunt_id' => $emprunt->id,
                'utilisateur_id' => $emprunt->utilisateur_id,
                'action' => 'Emprunt',
                'date_action' => $emprunt->date_emprunt,
                'details' => "Livre '{$emprunt->livre->title}' emprunté par {$emprunt->utilisateur->name}"
            ]);
            
            // 2. Retour action (when book was returned)
            HistoriqueEmprunt::create([
                'emprunt_id' => $emprunt->id,
                'utilisateur_id' => $emprunt->utilisateur_id,
                'action' => 'Retour',
                'date_action' => $emprunt->date_retour_eff,
                'details' => "Livre '{$emprunt->livre->title}' retourné par {$emprunt->utilisateur->name}"
            ]);
            
            // 3. Optional: Add some additional actions for variety
            if (rand(0, 1)) {
                $randomDate = Carbon::parse($emprunt->date_emprunt)->addDays(rand(1, 5));
                HistoriqueEmprunt::create([
                    'emprunt_id' => $emprunt->id,
                    'utilisateur_id' => $emprunt->utilisateur_id,
                    'action' => 'Rappel',
                    'date_action' => $randomDate,
                    'details' => "Rappel envoyé pour le livre '{$emprunt->livre->title}'"
                ]);
            }
        }
        
        $this->command->info('HistoriqueEmprunt records created successfully!');
        $this->command->info('Created ' . HistoriqueEmprunt::count() . ' historique records');
    }
}