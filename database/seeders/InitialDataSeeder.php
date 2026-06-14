<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Challenge;
use App\Models\Badge;
use App\Models\Event;
use Illuminate\Support\Facades\Hash;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        User::updateOrCreate(
            ['email' => 'admin@siklim.com'],
            [
                'name' => 'Siklim Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'points' => 0,
                'streak' => 0,
                'carbon_saved' => 0,
            ]
        );

        // Create Demo User
        User::updateOrCreate(
            ['email' => 'user@siklim.com'],
            [
                'name' => 'Maya Johnson',
                'password' => Hash::make('password'),
                'role' => 'user',
                'points' => 8750,
                'streak' => 23,
                'carbon_saved' => 142.6,
                'challenges_completed' => 87,
                'location' => 'Jakarta, Indonesia'
            ]
        );

        // Challenges
        $challenges = [
            [
                'title' => 'Bike to Work',
                'description' => 'Use a bicycle or e-bike for your daily commute instead of a car or motorcycle.',
                'category' => 'transport',
                'difficulty' => 'medium',
                'points' => 50,
                'co2_saved' => 2.3,
                'image_url' => 'https://images.unsplash.com/photo-11763041821558-71301407ded8?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&w=400',
            ],
            [
                'title' => 'Zero Waste Lunch',
                'description' => 'Prepare a plant-based lunch with zero single-use plastic packaging.',
                'category' => 'food',
                'difficulty' => 'easy',
                'points' => 30,
                'co2_saved' => 1.1,
                'image_url' => 'https://images.unsplash.com/photo-11770914755925-6468b9050176?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&w=400',
            ],
            [
                'title' => 'Recycling Sort',
                'description' => 'Properly sort your household recycling into correct bins. Learn the 3R rule.',
                'category' => 'waste',
                'difficulty' => 'easy',
                'points' => 20,
                'co2_saved' => 0.8,
                'image_url' => 'https://images.unsplash.com/photo-11761494907751-faf14c99f7ed?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&w=400',
            ],
            [
                'title' => 'Electric Vehicle Test Drive',
                'description' => 'Visit a dealership or service and test drive an electric vehicle. Learn about EV technology and charging infrastructure.',
                'category' => 'transport',
                'difficulty' => 'hard',
                'points' => 100,
                'co2_saved' => 0.2,
                'image_url' => 'https://images.unsplash.com/photo-1593941707882-a5bba14938c7?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&w=400',
            ],
            [
                'title' => 'Install Solar Panel App',
                'description' => 'Download and set up a solar energy monitoring app on your phone. Track your household energy usage and identify peak...',
                'category' => 'energy',
                'difficulty' => 'hard',
                'points' => 80,
                'co2_saved' => 5.2,
                'image_url' => 'https://images.unsplash.com/photo-1508514177221-188b1cf16e9d?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&w=400',
            ],
            [
                'title' => 'Beach/Park Cleanup',
                'description' => 'Spend 30 minutes picking up litter at a local beach, park, or green space. Help keep our ecosystems clean.',
                'category' => 'nature',
                'difficulty' => 'medium',
                'points' => 70,
                'co2_saved' => 1.8,
                'image_url' => 'https://images.unsplash.com/photo-1618477461853-cf6ed80faba5?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&w=400',
            ],
            [
                'title' => 'Tree Planting Day',
                'description' => 'Participate in a local tree planting event or plant a native tree in your own backyard.',
                'category' => 'nature',
                'difficulty' => 'medium',
                'points' => 120,
                'co2_saved' => 22.0,
                'image_url' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&w=400',
            ],
            [
                'title' => 'Zero Waste Grocery Shopping',
                'description' => 'Shop at a bulk store or use your own reusable containers for all your groceries.',
                'category' => 'food',
                'difficulty' => 'easy',
                'points' => 40,
                'co2_saved' => 0.5,
                'image_url' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&w=400',
            ]
        ];

        foreach ($challenges as $c) {
            Challenge::updateOrCreate(['title' => $c['title']], $c);
        }

        // Add Sample Events
        $user = User::where('email', 'user@siklim.com')->first();
        if ($user) {
            $events = [
                [
                    'name' => 'Mangrove Planting Day',
                    'type' => 'nature',
                    'date' => now()->addDays(10),
                    'participants' => 45,
                    'x' => 42.5,
                    'y' => 55.2,
                    'description' => 'A community effort to restore mangrove forests along the coast.',
                    'status' => 'accepted',
                    'user_id' => $user->id,
                ],
                [
                    'name' => 'City Park Cleanup',
                    'type' => 'cleanup',
                    'date' => now()->addDays(15),
                    'participants' => 30,
                    'x' => 58.2,
                    'y' => 42.1,
                    'description' => 'Help us keep our city parks clean and beautiful.',
                    'status' => 'accepted',
                    'user_id' => $user->id,
                ],
                [
                    'name' => 'Eco-Friendly Living Workshop',
                    'type' => 'workshop',
                    'date' => now()->addDays(20),
                    'participants' => 20,
                    'x' => 48.9,
                    'y' => 48.5,
                    'description' => 'Learn how to reduce your carbon footprint in daily life.',
                    'status' => 'pending',
                    'user_id' => $user->id,
                ],
            ];

            foreach ($events as $event) {
                Event::updateOrCreate(['name' => $event['name']], $event);
            }
        }

        // Badges
        $badges = [
            ['name' => 'First Step', 'emoji' => '🌱', 'rarity' => 'common'],
            ['name' => 'Week Warrior', 'emoji' => '🔥', 'rarity' => 'common'],
            ['name' => 'Eco Rookie', 'emoji' => '🌿', 'rarity' => 'common'],
            ['name' => 'Month Master', 'emoji' => '💪', 'rarity' => 'rare'],
            ['name' => 'Community Builder', 'emoji' => '👥', 'rarity' => 'rare'],
            ['name' => 'Energy Saver', 'emoji' => '⚡', 'rarity' => 'rare'],
        ];

        foreach ($badges as $b) {
            Badge::updateOrCreate(['name' => $b['name']], $b);
        }
    }
}
