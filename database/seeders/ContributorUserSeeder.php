<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContributorUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Contributor User',
            'email' => 'contributor@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'contributor',
        ]);
    }
}
