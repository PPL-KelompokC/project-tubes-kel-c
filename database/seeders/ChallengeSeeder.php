<?php

namespace Database\Seeders;

use App\Models\Challenge;
use Illuminate\Database\Seeder;

class ChallengeSeeder extends Seeder
{
    public function run(): void
    {
        $today = today()->toDateString();

        $challenges = [
            // ── Daily challenges for today ────────────────────────
            [
                'title'        => 'Bike to Work or School',
                'description'  => 'Use a bicycle instead of a motorized vehicle for your commute today. Any distance counts — every km helps!',
                'category'     => 'Transport',
                'difficulty'   => 'Medium',
                'points'       => 80,
                'co2_saved'    => 2.30,
                'image_url'    => 'https://images.unsplash.com/photo-1507035895480-2b3156c31fc8?w=800&q=80',
                'is_daily'     => true,
                'active_date'  => $today,
            ],
            [
                'title'        => 'Cook a Plant-Based Meal',
                'description'  => 'Prepare a meal with no meat or dairy. Vegetables, legumes, grains — show us your eco-friendly dish.',
                'category'     => 'Food',
                'difficulty'   => 'Easy',
                'points'       => 50,
                'co2_saved'    => 1.50,
                'image_url'    => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=800&q=80',
                'is_daily'     => true,
                'active_date'  => $today,
            ],
            [
                'title'        => 'Recycle 5 Items Today',
                'description'  => 'Collect and properly sort at least 5 recyclable items (plastic bottles, paper, cans) into the correct bin.',
                'category'     => 'Waste',
                'difficulty'   => 'Easy',
                'points'       => 40,
                'co2_saved'    => 0.80,
                'image_url'    => 'https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?w=800&q=80',
                'is_daily'     => true,
                'active_date'  => $today,
            ],

            // ── Non-daily library of challenges ──────────────────
            [
                'title'        => 'Use Public Transport',
                'description'  => 'Take a bus, train, or MRT instead of driving your personal vehicle. Take a photo at the stop/station.',
                'category'     => 'Transport',
                'difficulty'   => 'Easy',
                'points'       => 40,
                'co2_saved'    => 1.20,
                'image_url'    => 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=800&q=80',
                'is_daily'     => false,
                'active_date'  => null,
            ],
            [
                'title'        => 'Bring a Reusable Bag',
                'description'  => 'Go shopping with a reusable tote or bag — no plastic bags! Show the bag with groceries or at the store.',
                'category'     => 'Waste',
                'difficulty'   => 'Easy',
                'points'       => 30,
                'co2_saved'    => 0.20,
                'image_url'    => 'https://images.unsplash.com/photo-1584473457406-6240486418e9?w=800&q=80',
                'is_daily'     => false,
                'active_date'  => null,
            ],
            [
                'title'        => 'Solar Phone Charging',
                'description'  => 'Charge your device using a solar charger or via renewable energy source. Show the setup!',
                'category'     => 'Energy',
                'difficulty'   => 'Hard',
                'points'       => 120,
                'co2_saved'    => 0.50,
                'image_url'    => 'https://images.unsplash.com/photo-1509391366360-2e959784a276?w=800&q=80',
                'is_daily'     => false,
                'active_date'  => null,
            ],
            [
                'title'        => 'Plant a Seedling',
                'description'  => 'Plant a seedling or sapling in a pot or garden. Show the plant being placed into soil.',
                'category'     => 'Waste',
                'difficulty'   => 'Medium',
                'points'       => 100,
                'co2_saved'    => 5.00,
                'image_url'    => 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=800&q=80',
                'is_daily'     => false,
                'active_date'  => null,
            ],
            [
                'title'        => 'Meatless Monday Meal',
                'description'  => 'Skip meat for an entire day and show us your best meatless meal, snack, or lunchbox.',
                'category'     => 'Food',
                'difficulty'   => 'Easy',
                'points'       => 50,
                'co2_saved'    => 2.00,
                'image_url'    => 'https://images.unsplash.com/photo-1540914124281-342587941389?w=800&q=80',
                'is_daily'     => false,
                'active_date'  => null,
            ],
            [
                'title'        => 'Cold-Water Laundry',
                'description'  => 'Wash your clothes in cold water instead of hot/warm. Take a photo of the machine settings at cold.',
                'category'     => 'Energy',
                'difficulty'   => 'Easy',
                'points'       => 35,
                'co2_saved'    => 0.60,
                'image_url'    => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&q=80',
                'is_daily'     => false,
                'active_date'  => null,
            ],
            [
                'title'        => 'Zero-Waste Lunch',
                'description'  => 'Bring lunch in reusable containers — no single-use plastic or packaging. Show your lunchbox!',
                'category'     => 'Waste',
                'difficulty'   => 'Medium',
                'points'       => 60,
                'co2_saved'    => 0.40,
                'image_url'    => 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?w=800&q=80',
                'is_daily'     => false,
                'active_date'  => null,
            ],
        ];

        foreach ($challenges as $data) {
            Challenge::firstOrCreate(
                ['title' => $data['title']],
                $data
            );
        }

        $this->command->info('Challenges seeded successfully! 3 daily challenges set for today.');
    }
}
