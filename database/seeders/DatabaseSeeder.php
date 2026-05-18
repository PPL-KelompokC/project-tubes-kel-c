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

        // Add Nayla's account to ensure it's never lost during deployment seeds
        $nayla = User::factory()->create([
            'name' => 'Nayla Sena',
            'email' => 'naylasena@gmail.com',
            'password' => bcrypt('password'), // Or a default password you prefer
            'role' => 'user',
            'points' => 1200,
            'streak' => 5,
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

        \App\Models\Event::create([
            'name' => 'Bike to Work Rally',
            'type' => 'transport',
            'date' => now()->addDays(3),
            'participants' => 120,
            'x' => 55.00,
            'y' => 42.00,
            'description' => 'Promote sustainable commuting by cycling together downtown.',
            'status' => 'accepted',
            'user_id' => $user->id,
        ]);

        \App\Models\Event::create([
            'name' => 'River Trash Pickup',
            'type' => 'cleanup',
            'date' => now()->addDays(6),
            'participants' => 0,
            'x' => 40.00,
            'y' => 55.00,
            'description' => 'Clean up plastic waste along the river banks.',
            'status' => 'pending',
            'user_id' => $user->id,
        ]);

        \App\Models\Event::create([
            'name' => 'Climate Change Talk',
            'type' => 'awareness',
            'date' => now()->addDays(8),
            'participants' => 80,
            'x' => 60.00,
            'y' => 30.00,
            'description' => 'A public talk on the local impact of climate change.',
            'status' => 'accepted',
            'user_id' => $user->id,
        ]);

        \App\Models\Event::create([
            'name' => 'E-Waste Collection Drive',
            'type' => 'awareness',
            'date' => now()->addDays(4),
            'participants' => 0,
            'x' => 48.00,
            'y' => 38.00,
            'description' => 'Drop off old electronics for responsible recycling.',
            'status' => 'pending',
            'user_id' => $user->id,
        ]);

        \App\Models\Event::create([
            'name' => 'DIY Composting 101',
            'type' => 'workshop',
            'date' => now()->addDays(12),
            'participants' => 25,
            'x' => 35.00,
            'y' => 45.00,
            'description' => 'Hands-on session on building a home compost bin.',
            'status' => 'rejected',
            'user_id' => $user->id,
        ]);

        \App\Models\Event::create([
            'name' => 'Carpooling Awareness Week',
            'type' => 'transport',
            'date' => now()->addDays(9),
            'participants' => 0,
            'x' => 42.00,
            'y' => 58.00,
            'description' => 'Spread the word about ride-sharing to cut emissions.',
            'status' => 'pending',
            'user_id' => $user->id,
        ]);

        \App\Models\Event::create([
            'name' => 'Park Restoration Day',
            'type' => 'nature',
            'date' => now()->addDays(14),
            'participants' => 50,
            'x' => 58.00,
            'y' => 40.00,
            'description' => 'Restore native plants and remove invasive species.',
            'status' => 'rejected',
            'user_id' => $user->id,
        ]);

        \App\Models\Event::create([
            'name' => 'Beach Cleanup Marathon',
            'type' => 'cleanup',
            'date' => now()->addDays(2),
            'participants' => 200,
            'x' => 50.00,
            'y' => 52.00,
            'description' => 'A full-day beach cleanup event with prizes for top collectors.',
            'status' => 'rejected',
            'user_id' => $user->id,
        ]);

        \App\Models\Event::create([
            'name' => 'Recycling Workshop',
            'type' => 'workshop',
            'date' => now()->addDays(11),
            'participants' => 0,
            'x' => 62.00,
            'y' => 48.00,
            'description' => 'Learn how to sort and recycle household waste properly.',
            'status' => 'pending',
            'user_id' => $user->id,
        ]);

        \App\Models\Event::create([
            'name' => 'Green Energy Expo',
            'type' => 'awareness',
            'date' => now()->addDays(20),
            'participants' => 150,
            'x' => 44.00,
            'y' => 36.00,
            'description' => 'Explore renewable energy solutions from local startups.',
            'status' => 'accepted',
            'user_id' => $user->id,
        ]);

        // Seed Activity Feeds
        $this->call(FeedSeeder::class);
    }
}
