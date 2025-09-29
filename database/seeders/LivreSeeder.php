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
            ['title' => 'The Great Gatsby'],
            ['title' => 'To Kill a Mockingbird'],
            ['title' => '1984'],
            ['title' => 'Pride and Prejudice'],
            ['title' => 'The Catcher in the Rye'],
        ];

        foreach ($livres as $livre) {
            Livre::create($livre);
        }
    }
}

