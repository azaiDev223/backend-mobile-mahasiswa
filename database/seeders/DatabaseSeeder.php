<?php

namespace Database\Seeders;
use Illuminate\Support\Str;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'email_verified_at' => now(),
    'password' => bcrypt('password'),
    'remember_token' => Str::random(10),
    'role' => 'admin', // tambahkan ini (atau sesuai nilai yang kamu mau)
]);

    }
}
