<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Livre;
use App\Models\Emprunt;
use App\Models\Categorie;
use Carbon\Carbon;

class ReadingPersonalityTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test categories if they don't exist
        $categories = [
            ['nom' => 'Science-Fiction', 'description' => 'Livres de science-fiction'],
            ['nom' => 'Voyage', 'description' => 'Livres de voyage et découverte'],
            ['nom' => 'Histoire', 'description' => 'Livres historiques'],
            ['nom' => 'Philosophie', 'description' => 'Essais philosophiques'],
            ['nom' => 'Romance', 'description' => 'Romans d\'amour'],
            ['nom' => 'Mystère', 'description' => 'Romans policiers et mystères'],
        ];

        foreach ($categories as $categoryData) {
            Categorie::firstOrCreate(
                ['nom' => $categoryData['nom']],
                $categoryData
            );
        }

        // Get or create a test user
        $testUser = User::firstOrCreate(
            ['email' => 'test@readingpersonality.com'],
            [
                'name' => 'Test Reader',
                'password' => bcrypt('password'),
                'role' => 'user'
            ]
        );

        // Create diverse test books
        $books = [
            [
                'title' => 'Dune',
                'author' => 'Frank Herbert',
                'genre' => 'Science-Fiction',
                'description' => 'Une épopée spatiale dans un futur lointain',
                'categorie_id' => Categorie::where('nom', 'Science-Fiction')->first()->id,
                'user_id' => $testUser->id,
                'disponibilite' => true,
                'visibilite' => 'public'
            ],
            [
                'title' => 'Le Petit Prince',
                'author' => 'Antoine de Saint-Exupéry',
                'genre' => 'Philosophie',
                'description' => 'Un conte philosophique sur l\'amitié et la vie',
                'categorie_id' => Categorie::where('nom', 'Philosophie')->first()->id,
                'user_id' => $testUser->id,
                'disponibilite' => true,
                'visibilite' => 'public'
            ],
            [
                'title' => '1984',
                'author' => 'George Orwell',
                'genre' => 'Science-Fiction',
                'description' => 'Une dystopie sur la surveillance et le contrôle',
                'categorie_id' => Categorie::where('nom', 'Science-Fiction')->first()->id,
                'user_id' => $testUser->id,
                'disponibilite' => true,
                'visibilite' => 'public'
            ],
            [
                'title' => 'Voyage au centre de la Terre',
                'author' => 'Jules Verne',
                'genre' => 'Voyage',
                'description' => 'Une aventure extraordinaire sous terre',
                'categorie_id' => Categorie::where('nom', 'Voyage')->first()->id,
                'user_id' => $testUser->id,
                'disponibilite' => true,
                'visibilite' => 'public'
            ],
            [
                'title' => 'Les Misérables',
                'author' => 'Victor Hugo',
                'genre' => 'Histoire',
                'description' => 'Un roman historique sur la société française',
                'categorie_id' => Categorie::where('nom', 'Histoire')->first()->id,
                'user_id' => $testUser->id,
                'disponibilite' => true,
                'visibilite' => 'public'
            ],
            [
                'title' => 'L\'Étranger',
                'author' => 'Albert Camus',
                'genre' => 'Philosophie',
                'description' => 'Un roman existentialiste sur l\'absurdité',
                'categorie_id' => Categorie::where('nom', 'Philosophie')->first()->id,
                'user_id' => $testUser->id,
                'disponibilite' => true,
                'visibilite' => 'public'
            ],
            [
                'title' => 'Le Seigneur des Anneaux',
                'author' => 'J.R.R. Tolkien',
                'genre' => 'Fantasy',
                'description' => 'Une épopée fantastique en Terre du Milieu',
                'categorie_id' => Categorie::where('nom', 'Science-Fiction')->first()->id,
                'user_id' => $testUser->id,
                'disponibilite' => true,
                'visibilite' => 'public'
            ],
            [
                'title' => 'Le Tour du monde en 80 jours',
                'author' => 'Jules Verne',
                'genre' => 'Voyage',
                'description' => 'Une course contre la montre autour du globe',
                'categorie_id' => Categorie::where('nom', 'Voyage')->first()->id,
                'user_id' => $testUser->id,
                'disponibilite' => true,
                'visibilite' => 'public'
            ]
        ];

        foreach ($books as $bookData) {
            Livre::firstOrCreate(
                ['title' => $bookData['title'], 'author' => $bookData['author']],
                $bookData
            );
        }

        // Create borrowing history for the test user
        $books = Livre::where('user_id', $testUser->id)->get();
        
        if ($books->count() >= 3) {
            // Create completed borrowings (at least 3 for personality generation)
            $borrowingDates = [
                Carbon::now()->subDays(30),
                Carbon::now()->subDays(25),
                Carbon::now()->subDays(20),
                Carbon::now()->subDays(15),
                Carbon::now()->subDays(10),
                Carbon::now()->subDays(5),
            ];

            foreach ($borrowingDates as $index => $borrowDate) {
                if ($index < $books->count()) {
                    $book = $books[$index];
                    $returnDate = $borrowDate->copy()->addDays(rand(3, 14));
                    
                    Emprunt::firstOrCreate(
                        [
                            'utilisateur_id' => $testUser->id,
                            'livre_id' => $book->id,
                            'date_emprunt' => $borrowDate,
                        ],
                        [
                            'date_retour_prev' => $borrowDate->copy()->addDays(14),
                            'date_retour_eff' => $returnDate,
                            'statut' => 'retourne',
                            'penalite' => 0,
                            'commentaire' => 'Test borrowing for personality analysis'
                        ]
                    );
                }
            }
        }

        $this->command->info('Test data created successfully!');
        $this->command->info('Test user: test@readingpersonality.com');
        $this->command->info('Password: password');
        $this->command->info('Created ' . $books->count() . ' books and ' . Emprunt::where('utilisateur_id', $testUser->id)->count() . ' borrowings');
    }
}