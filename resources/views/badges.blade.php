@extends('layouts.app')

@section('title', 'Badges & Achievements - EcoChallenge')

@section('content')
@php
    $badges = [
        ['id' => 1, 'name' => 'First Step', 'description' => 'Complete your first challenge', 'emoji' => '🌱', 'category' => 'milestone', 'rarity' => 'common', 'unlocked' => true, 'unlockedDate' => '2024-01-20', 'xpReward' => 50],
        ['id' => 2, 'name' => 'Week Warrior', 'description' => 'Maintain a 7-day streak', 'emoji' => '🔥', 'category' => 'streak', 'rarity' => 'common', 'unlocked' => true, 'unlockedDate' => '2024-01-27', 'xpReward' => 100],
        ['id' => 3, 'name' => 'Eco Rookie', 'description' => 'Save 10kg of CO₂', 'emoji' => '🌿', 'category' => 'carbon', 'rarity' => 'common', 'unlocked' => true, 'unlockedDate' => '2024-02-05', 'xpReward' => 150],
        ['id' => 4, 'name' => 'Transport Hero', 'description' => 'Complete 10 transport challenges', 'emoji' => '🚴', 'category' => 'category', 'rarity' => 'rare', 'unlocked' => false, 'unlockedDate' => null, 'xpReward' => 200],
        ['id' => 5, 'name' => 'Month Master', 'description' => 'Maintain a 30-day streak', 'emoji' => '💪', 'category' => 'streak', 'rarity' => 'rare', 'unlocked' => true, 'unlockedDate' => '2024-02-15', 'xpReward' => 300],
        ['id' => 6, 'name' => 'Carbon Crusher', 'description' => 'Save 100kg of CO₂', 'emoji' => '💚', 'category' => 'carbon', 'rarity' => 'rare', 'unlocked' => false, 'unlockedDate' => null, 'xpReward' => 400],
        ['id' => 7, 'name' => 'Community Builder', 'description' => 'Refer 5 friends to the platform', 'emoji' => '👥', 'category' => 'social', 'rarity' => 'rare', 'unlocked' => true, 'unlockedDate' => '2024-03-01', 'xpReward' => 250],
        ['id' => 8, 'name' => 'Nature Keeper', 'description' => 'Plant 5 trees or saplings', 'emoji' => '🌳', 'category' => 'nature', 'rarity' => 'epic', 'unlocked' => false, 'unlockedDate' => null, 'xpReward' => 500],
        ['id' => 9, 'name' => 'Energy Saver', 'description' => 'Complete 15 energy challenges', 'emoji' => '⚡', 'category' => 'category', 'rarity' => 'rare', 'unlocked' => true, 'unlockedDate' => '2024-03-10', 'xpReward' => 200],
        ['id' => 10, 'name' => 'Zero Waste Hero', 'description' => 'Complete 20 waste challenges', 'emoji' => '♻️', 'category' => 'category', 'rarity' => 'epic', 'unlocked' => false, 'unlockedDate' => null, 'xpReward' => 600],
        ['id' => 11, 'name' => 'Top 10 Climber', 'description' => 'Reach top 10 on the leaderboard', 'emoji' => '🏆', 'category' => 'achievement', 'rarity' => 'epic', 'unlocked' => true, 'unlockedDate' => '2024-03-15', 'xpReward' => 750],
        ['id' => 12, 'name' => 'Earth Guardian', 'description' => 'Save 500kg of CO₂', 'emoji' => '🌍', 'category' => 'carbon', 'rarity' => 'legendary', 'unlocked' => false, 'unlockedDate' => null, 'xpReward' => 2000],
    ];

    $unlockedCount = collect($badges)->where('unlocked', true)->count();
    $totalCount = count($badges);
    $totalPct = round(($unlockedCount / $totalCount) * 100);

    $rarityConfig = [
        'common' => ['label' => 'Common', 'bg' => 'bg-gray-50', 'text' => 'text-gray-600', 'border' => 'border-gray-200', 'glow' => ''],
        'rare' => ['label' => 'Rare', 'bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'glow' => 'shadow-blue-100'],
        'epic' => ['label' => 'Epic', 'bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'border' => 'border-purple-200', 'glow' => 'shadow-purple-100'],
        'legendary' => ['label' => 'Legendary', 'bg' => 'bg-gradient-to-br from-yellow-50 to-amber-50', 'text' => 'text-amber-700', 'border' => 'border-yellow-300', 'glow' => 'shadow-yellow-100'],
    ];

    $filter = request('filter', 'all');
    $categoryFilter = request('category', 'all');

    $filteredBadges = collect($badges)->filter(function($b) use ($filter, $categoryFilter) {
        if ($filter === 'unlocked' && !$b['unlocked']) return false;
        if ($filter === 'locked' && $b['unlocked']) return false;
        if ($categoryFilter !== 'all' && $b['category'] !== $categoryFilter) return false;
        return true;
    });

    $categories = ['all', 'milestone', 'streak', 'carbon', 'category', 'social', 'achievement', 'nature'];
@endphp

<div class="p-4 lg:p-6 max-w-5xl mx-auto space-y-6">
    <!-- Progress overview -->
    <div class="rounded-3xl p-6 text-white relative overflow-hidden animate-bounce-in shadow-lg" style="background: linear-gradient(135deg, #15803d 0%, #047857 45%, #0369a1 100%);">
        <!-- Decorative overlay circles -->
        <div class="absolute inset-0 rounded-3xl" style="background-image: radial-gradient(circle at 15% 75%, rgba(52,211,153,0.18) 0%, transparent 55%), radial-gradient(circle at 85% 15%, rgba(56,189,248,0.15) 0%, transparent 55%);"></div>
        <div class="relative z-10 flex items-center justify-between gap-6">
            <div>
                <p class="text-green-200 text-sm font-medium mb-1">Badge Collection</p>
                <h2 class="text-2xl font-black">{{ $unlockedCount }} / {{ $totalCount }} Unlocked</h2>
                <p class="text-green-100 text-xs mt-1">{{ $totalCount - $unlockedCount }} more to collect!</p>
            </div>
            <div class="relative w-24 h-24">
                <svg class="w-24 h-24 -rotate-90" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="40" fill="none" stroke="rgba(255,255,255,0.2)" strokeWidth="10" />
                    <circle
                        cx="50" cy="50" r="40"
                        fill="none"
                        stroke="white"
                        strokeWidth="10"
                        strokeLinecap="round"
                        stroke-dasharray="251.32"
                        stroke-dashoffset="{{ 251.32 * (1 - $totalPct / 100) }}"
                        class="transition-all duration-1000"
                    />
                </svg>
                <div class="absolute inset-0 flex items-center justify-center flex-col">
                    <span class="text-2xl font-black text-white">{{ $totalPct }}%</span>
                </div>
            </div>
        </div>
        <!-- Rarity breakdown -->
        <div class="mt-4 grid grid-cols-4 gap-2">
            @foreach(['common', 'rare', 'epic', 'legendary'] as $r)
                @php 
                    $count = collect($badges)->where('rarity', $r)->where('unlocked', true)->count();
                    $total = collect($badges)->where('rarity', $r)->count();
                @endphp
                <div class="bg-white/15 backdrop-blur-sm rounded-xl p-2 text-center">
                    <p class="text-white font-bold text-sm">{{ $count }}/{{ $total }}</p>
                    <p class="text-green-200 text-[10px] capitalize">{{ $r }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Filters -->
    <div class="flex flex-col sm:flex-row gap-3">
        <div class="flex gap-1.5">
            @foreach(['all' => 'All', 'unlocked' => 'Unlocked', 'locked' => 'Locked'] as $f => $label)
                <a
                    href="{{ route('badges', array_merge(request()->query(), ['filter' => $f])) }}"
                    class="px-4 py-2 rounded-xl text-xs font-semibold transition-all border {{ $filter === $f ? 'bg-green-600 text-white border-green-600' : 'bg-white text-gray-600 border-gray-200 hover:border-green-300' }}"
                >
                    {{ $label }}
                </a>
            @endforeach
        </div>
        <div class="flex gap-1.5 overflow-x-auto pb-1 hide-scrollbar">
            @foreach($categories as $cat)
                @php $isActive = $categoryFilter === $cat; @endphp
                <a
                    href="{{ route('badges', array_merge(request()->query(), ['category' => $cat])) }}"
                    class="flex-shrink-0 px-3 py-2 rounded-xl text-xs font-semibold transition-all border {{ $isActive ? 'bg-green-100 text-green-700 border-green-200' : 'bg-white text-gray-500 border-gray-200 hover:border-green-300' }}"
                >
                    {{ ucfirst($cat) }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Badge Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
        @foreach($filteredBadges as $i => $badge)
            @php $rc = $rarityConfig[$badge['rarity']] ?? $rarityConfig['common']; @endphp
            <div class="relative p-4 rounded-2xl border text-center transition-all animate-count-in {{ $badge['unlocked'] ? $rc['bg'] . ' ' . $rc['border'] . ' shadow-md ' . $rc['glow'] . ' hover:scale-105' : 'bg-gray-50 border-gray-200 opacity-60' }}" style="animation-delay: {{ $i * 0.04 }}s">
                @if($badge['rarity'] === 'legendary' && $badge['unlocked'])
                    <div class="absolute inset-0 rounded-2xl overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-yellow-100/50 to-amber-100/50"></div>
                    </div>
                @endif
                <div class="relative z-10">
                    <div class="text-4xl mb-2 {{ !$badge['unlocked'] ? 'grayscale' : '' }}">
                        @if(!$badge['unlocked'])
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400 mx-auto"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        @else
                            <span>{{ $badge['emoji'] }}</span>
                        @endif
                    </div>
                    <p class="text-xs font-bold leading-tight {{ $badge['unlocked'] ? $rc['text'] : 'text-gray-500' }}">
                        {{ $badge['name'] }}
                    </p>
                    <div class="mt-1.5 text-[10px] px-2 py-0.5 rounded-full inline-block font-semibold {{ $badge['rarity'] === 'legendary' ? 'bg-yellow-200 text-yellow-800' : ($badge['rarity'] === 'epic' ? 'bg-purple-100 text-purple-700' : ($badge['rarity'] === 'rare' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600')) }}">
                        {{ $rc['label'] }}
                    </div>
                    @if($badge['unlocked'])
                        <div class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-green-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-[9px]">✓</span>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    @if($filteredBadges->count() === 0)
        <div class="text-center py-16">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-gray-200 mx-auto mb-4"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <h3 class="text-base font-bold text-gray-700 mb-2">No badges found</h3>
            <p class="text-sm text-gray-500 mb-4">Try adjusting your filters</p>
            <a href="{{ route('badges') }}" class="inline-block bg-green-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-green-700 transition-colors">
                Clear filters
            </a>
        </div>
    @endif
</div>
@endsection
