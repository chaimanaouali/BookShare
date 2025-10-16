<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'nom' => 'Fiction',
                'description' => 'Novels, short stories, and other fictional works'
            ],
            [
                'nom' => 'Non-Fiction',
                'description' => 'Biographies, essays, and factual works'
            ],
            [
                'nom' => 'Science Fiction',
                'description' => 'Science fiction novels and stories'
            ],
            [
                'nom' => 'Fantasy',
                'description' => 'Fantasy novels and magical stories'
            ],
            [
                'nom' => 'Mystery',
                'description' => 'Detective stories and mystery novels'
            ],
            [
                'nom' => 'Romance',
                'description' => 'Romance novels and love stories'
            ],
            [
                'nom' => 'Thriller',
                'description' => 'Suspenseful and thrilling stories'
            ],
            [
                'nom' => 'History',
                'description' => 'Historical books and accounts'
            ],
            [
                'nom' => 'Biography',
                'description' => 'Life stories and memoirs'
            ],
            [
                'nom' => 'Self-Help',
                'description' => 'Personal development and self-improvement books'
            ],
            [
                'nom' => 'Business',
                'description' => 'Business and entrepreneurship books'
            ],
            [
                'nom' => 'Technology',
                'description' => 'Technology and programming books'
            ],
            [
                'nom' => 'Education',
                'description' => 'Educational and academic books'
            ],
            [
                'nom' => 'Health & Fitness',
                'description' => 'Health, fitness, and wellness books'
            ],
            [
                'nom' => 'Travel',
                'description' => 'Travel guides and adventure books'
            ]
        ];

        foreach ($categories as $category) {
            \App\Models\Categorie::create($category);
        }
    }
}
