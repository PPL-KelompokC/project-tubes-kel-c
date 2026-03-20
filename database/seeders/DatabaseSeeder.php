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
        $admin = User::factory()->create([
            'name' => 'Admin Siklim',
            'email' => 'admin@siklim.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'user@siklim.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        \App\Models\Event::create([
            'name' => 'SF Bay Cleanup',
            'type' => 'cleanup',
            'date' => now()->addDays(5),
            'participants' => 45,
            'x' => 52.00,
            'y' => 48.00,
            'description' => 'Join us to clean up Ocean Beach!',
            'status' => 'accepted',
            'user_id' => $user->id,
        ]);

        \App\Models\Event::create([
            'name' => 'Solar Workshop',
            'type' => 'workshop',
            'date' => now()->addDays(7),
            'participants' => 30,
            'x' => 65.00,
            'y' => 35.00,
            'description' => 'Learn how to install home solar.',
            'status' => 'accepted',
            'user_id' => $user->id,
        ]);

        \App\Models\Event::create([
            'name' => 'Urban Farming Day',
            'type' => 'nature',
            'date' => now()->addDays(10),
            'participants' => 60,
            'x' => 38.00,
            'y' => 50.00,
            'description' => 'Learn to grow your own food.',
            'status' => 'accepted',
            'user_id' => $user->id,
        ]);

        \App\Models\Event::create([
            'name' => 'Mangrove Planting',
            'type' => 'nature',
            'date' => now()->addDays(15),
            'participants' => 0,
            'x' => 45.00,
            'y' => 60.00,
            'description' => 'Help us plant mangroves to protect our coast.',
            'status' => 'pending',
            'user_id' => $user->id,
        ]);
    }
}
