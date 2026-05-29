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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        \App\Models\User::create([
        'name' => 'Super Admin',
        'email' => 'admin@adzkia.ac.id',
        'password' => \Illuminate\Support\Facades\Hash::make('password123'), // Password Anda
        'role' => 'super_admin',
        'divisi' => 'Pusat',
    ]);
    }
    
}
