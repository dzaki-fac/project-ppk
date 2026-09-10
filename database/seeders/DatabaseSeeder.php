<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => 'password123',
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => 'password123',
                'role' => 'user',
            ]
        );

        User::updateOrCreate(
            ['email' => 'galvin@example.com'],
            [
                'name' => 'Galvin',
                'password' => 'galvin123',
                'role' => 'user',
            ]
        );
    }
}
