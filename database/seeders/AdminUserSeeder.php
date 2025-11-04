<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
        ]);

        // Create a regular test user
        User::create([
            'name' => 'Test User',
            'email' => 'user@test.com',
            'password' => Hash::make('user123'),
            'is_admin' => false,
        ]);
    }
}