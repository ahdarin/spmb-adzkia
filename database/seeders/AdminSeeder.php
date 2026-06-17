<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@adzkia.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'divisi' => 'Pusat',
        ]);
    }
}
