<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a specific user for development login
        User::factory()->create([
            'name' => 'Dev User',
            'email' => 'dev@ote.com',
            'password' => Hash::make('password'),
        ]);

        // Create a few other random users
        User::factory()->count(4)->create();
    }
}
