<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Livre;

class LivreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $livres = [
            ['title' => 'Le Petit Prince'],
            ['title' => 'The Great Gatsby'],
            ['title' => 'To Kill a Mockingbird'],
            ['title' => '1984'],
            ['title' => 'L\'Étranger'],
            ['title' => 'Harry Potter et la Pierre Philosophale'],
            ['title' => 'Le Seigneur des Anneaux'],
            ['title' => 'Dune'],
            ['title' => 'Fondation'],
            ['title' => 'Les Misérables'],
            ['title' => 'Don Quichotte'],
            ['title' => 'Hamlet'],
            ['title' => 'Pride and Prejudice'],
            ['title' => 'The Catcher in the Rye'],
        ];

        foreach ($livres as $livre) {
            Livre::create($livre);
        }
    }
}

