<?php

namespace Database\Seeders;

use App\Models\Feed;
use App\Models\User;
use Illuminate\Database\Seeder;

class FeedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users
        $users = User::whereNotIn('role', ['admin'])->limit(5)->get();

        if ($users->isEmpty()) {
            // Create some test users if none exist
            $users = User::factory(5)->create(['role' => 'user']);
        }

        // Sample captions
        $captions = [
            'Just completed the Bike to Work challenge! 🚴 Feeling great and saving the planet one pedal at a time.',
            'Community garden is looking amazing today. Finished the plant a sapling challenge and learned so much about native species! 🌱',
            'Sorted recycling properly and learned what actually can be recycled in SF. Did you know soft plastics can\'t go in the bin? ♻️',
            'Started my zero-waste journey today! It\'s challenging but so rewarding. Every small action counts. 🌍',
            'Just participated in a local cleanup drive. The amount of trash we collected was eye-opening. We must do better! 🧹',
            'Made my first homemade compost bin. Let\'s see how this goes! 🪴',
            'Switched to reusable shopping bags today. Small step, big impact! 🛍️',
            'Completed my carbon footprint assessment. Time to make some changes for the better. 📊',
            'Supporting local farmers market this week. Fresh produce + lower carbon emissions = win-win! 🥕',
            'Started cycling to work! No more traffic jams and helping the environment. Best decision ever! 🚲',
        ];

        // Sample media (you can use placeholder URLs or actual image URLs)
        $mediaArrays = [
            [
                ['url' => 'https://images.unsplash.com/photo-1763041821558-71301407ded8?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&w=400'],
            ],
            [
                ['url' => 'https://images.unsplash.com/photo-1770914755925-6468b9050176?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&w=400'],
            ],
            [
                ['url' => 'https://images.unsplash.com/photo-1761494907751-faf14c99f7ed?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&w=400'],
            ],
            [
                ['url' => 'https://images.unsplash.com/photo-1771262029390-e54b7b80e5d9?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&w=400'],
            ],
            [
                ['url' => 'https://images.unsplash.com/photo-1442512595331-e89e39fc6ecf?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&w=400'],
            ],
            null, // Some feeds without media
            null,
        ];

        // Create feeds
        foreach ($users as $user) {
            // Create 2-4 feeds per user
            for ($i = 0; $i < rand(2, 4); $i++) {
                Feed::create([
                    'user_id' => $user->id,
                    'caption' => $captions[array_rand($captions)],
                    'media' => $mediaArrays[array_rand($mediaArrays)],
                    'status' => rand(0, 1) === 0 ? 'active' : (rand(0, 1) === 0 ? 'active' : 'hidden'),
                    'likes_count' => rand(5, 150),
                    'comments_count' => rand(0, 30),
                    'feed_type' => 'post',
                ]);
            }
        }
    }
}
