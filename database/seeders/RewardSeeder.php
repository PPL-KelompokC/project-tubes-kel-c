<?php

namespace Database\Seeders;

use App\Models\Reward;
use Illuminate\Database\Seeder;

class RewardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rewards = [
            [
                'name' => 'Plant a Real Tree',
                'description' => 'We will plant a real tree in your name in one of our reforestation projects around Indonesia.',
                'points_required' => 200,
                'stock' => null,
                'status' => 'active',
                'category' => 'donation',
            ],
            [
                'name' => 'Carbon Offset Certificate',
                'description' => 'Receive a digital certificate for offsetting 500kg of CO2 through our verified green partners.',
                'points_required' => 500,
                'stock' => null,
                'status' => 'active',
                'category' => 'digital',
            ],
            [
                'name' => 'Eco-friendly Tote Bag',
                'description' => 'A stylish and durable tote bag made from 100% recycled ocean plastic.',
                'points_required' => 350,
                'stock' => 50,
                'status' => 'active',
                'category' => 'physical',
            ],
            [
                'name' => 'Solar Phone Charger',
                'description' => 'Portable solar-powered charger for your mobile devices. Perfect for outdoor eco-adventures.',
                'points_required' => 800,
                'stock' => 10,
                'status' => 'coming_soon',
                'category' => 'physical',
            ],
            [
                'name' => 'Donate to WWF',
                'description' => 'Direct donation to WWF to help preserve endangered species and their habitats.',
                'points_required' => 250,
                'stock' => null,
                'status' => 'active',
                'category' => 'donation',
            ],
            [
                'name' => 'Bamboo Toothbrush Set',
                'description' => 'Pack of 4 biodegradable bamboo toothbrushes with charcoal-infused bristles.',
                'points_required' => 150,
                'stock' => 100,
                'status' => 'active',
                'category' => 'physical',
            ],
        ];

        foreach ($rewards as $reward) {
            Reward::create($reward);
        }
    }
}
