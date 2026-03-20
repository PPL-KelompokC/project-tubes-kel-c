@extends('layouts.app')

@section('title', 'Activity Feed - EcoChallenge')

@section('content')
@php
    $activityFeed = [
        [
            'id' => 1,
            'userName' => 'Alex Chen',
            'userAvatar' => 'https://images.unsplash.com/photo-1770364287160-4f8d9ff14975?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&w=100',
            'type' => 'challenge_complete',
            'content' => 'Just completed the Bike to Work challenge! 🚴 Feeling great and saving the planet one pedal at a time.',
            'image' => 'https://images.unsplash.com/photo-1763041821558-71301407ded8?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&w=400',
            'likes' => 24,
            'comments' => 5,
            'liked' => false,
            'timestamp' => '2 min ago',
            'challenge' => 'Bike to Work',
            'points' => 50,
            'co2' => 2.3,
        ],
        [
            'id' => 2,
            'userName' => 'Sofia Ramirez',
            'userAvatar' => 'https://images.unsplash.com/photo-1762708590808-c453c0e4fb0f?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&w=100',
            'type' => 'badge_earned',
            'content' => '🏆 Just unlocked the Month Master badge! 30 days of consistent climate action. Who\'s joining me?',
            'image' => null,
            'likes' => 87,
            'comments' => 12,
            'liked' => true,
            'timestamp' => '15 min ago',
            'badge' => 'Month Master',
            'xp' => 300,
        ],
        [
            'id' => 3,
            'userName' => 'James Okafor',
            'userAvatar' => null,
            'type' => 'challenge_complete',
            'content' => 'Community garden is looking 🌱 amazing today. Finished the plant a sapling challenge and learned so much about native species!',
            'image' => 'https://images.unsplash.com/photo-1770914755925-6468b9050176?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&w=400',
            'likes' => 41,
            'comments' => 8,
            'liked' => false,
            'timestamp' => '1 hour ago',
            'challenge' => 'Plant a Sapling',
            'points' => 60,
            'co2' => 3.5,
        ],
        [
            'id' => 4,
            'userName' => 'Maya Johnson',
            'userAvatar' => 'https://images.unsplash.com/photo-1656534439580-19c4de76498b?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&w=100',
            'type' => 'challenge_complete',
            'content' => 'Sorted recycling properly and learned what actually can be recycled in SF. Did you know soft plastics can\'t go in the bin? ♻️',
            'image' => 'https://images.unsplash.com/photo-1761494907751-faf14c99f7ed?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&w=400',
            'likes' => 33,
            'comments' => 7,
            'liked' => false,
            'timestamp' => '5 hours ago',
            'challenge' => 'Recycling Sort',
            'points' => 20,
            'co2' => 0.8,
            'isCurrentUser' => true,
        ],
    ];

    $typeConfig = [
        'challenge_complete' => ['label' => 'Challenge Complete', 'color' => 'text-green-700', 'bg' => 'bg-green-100', 'icon' => '✅'],
        'badge_earned' => ['label' => 'Badge Earned', 'color' => 'text-yellow-700', 'bg' => 'bg-yellow-100', 'icon' => '🏅'],
        'streak' => ['label' => 'Streak Milestone', 'color' => 'text-orange-700', 'bg' => 'bg-orange-100', 'icon' => '🔥'],
    ];
@endphp

<div class="p-4 lg:p-6 max-w-2xl mx-auto space-y-4">
    <!-- Post composer -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 animate-bounce-in">
        <div class="flex items-start gap-3">
            <img
                src="https://images.unsplash.com/photo-1656534439580-19c4de76498b?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&w=100"
                alt="You"
                class="w-10 h-10 rounded-full object-cover ring-2 ring-green-200 flex-shrink-0"
            />
            <div class="flex-1">
                <textarea
                    placeholder="Share your eco action today... 🌱"
                    class="w-full text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 resize-none focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-green-400"
                    rows="2"
                ></textarea>
                <div class="flex items-center justify-between mt-2">
                    <div class="flex gap-2">
                        <button class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                        </button>
                        <button class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors">
                            <span class="text-sm">🌿</span>
                        </button>
                    </div>
                    <button class="bg-green-600 hover:bg-green-700 text-white text-xs font-semibold px-4 py-2 rounded-xl transition-all flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" x2="11" y1="2" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                        Post
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Feed filter tabs -->
    <div class="flex gap-2">
        @foreach(['🌍 All', '✅ Challenges', '🏅 Badges', '🔥 Streaks'] as $tab)
            <button class="flex-1 py-2 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-600 hover:bg-green-50 hover:border-green-300 hover:text-green-700 transition-all">
                {{ $tab }}
            </button>
        @endforeach
    </div>

    <!-- Feed items -->
    @foreach($activityFeed as $i => $post)
        @php $tc = $typeConfig[$post['type']] ?? $typeConfig['challenge_complete']; @endphp
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden animate-count-in" style="animation-delay: {{ $i * 0.1 }}s">
            <!-- Header -->
            <div class="flex items-center gap-3 p-4 pb-3">
                @if($post['userAvatar'])
                    <img src="{{ $post['userAvatar'] }}" alt="{{ $post['userName'] }}" class="w-10 h-10 rounded-full object-cover ring-2 ring-gray-100" />
                @else
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white font-bold text-sm">
                        {{ substr($post['userName'], 0, 1) }}
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-bold {{ ($post['isCurrentUser'] ?? false) ? 'text-green-800' : 'text-gray-900' }}">{{ $post['userName'] }}</p>
                        @if($post['isCurrentUser'] ?? false)
                            <span class="text-[10px] bg-green-100 text-green-700 px-1.5 py-0.5 rounded-full font-medium">You</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $tc['bg'] }} {{ $tc['color'] }}">
                            {{ $tc['icon'] }} {{ $tc['label'] }}
                        </span>
                        <span class="text-[10px] text-gray-400">{{ $post['timestamp'] }}</span>
                    </div>
                </div>
                <button class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
                </button>
            </div>

            <!-- Content -->
            <div class="px-4 pb-3">
                <p class="text-sm text-gray-800 leading-relaxed">{{ $post['content'] }}</p>
            </div>

            <!-- Image -->
            @if($post['image'])
                <div class="px-4 pb-3">
                    <img src="{{ $post['image'] }}" alt="Post" class="w-full h-52 object-cover rounded-xl" />
                </div>
            @endif

            <!-- Stats bar -->
            @if(isset($post['challenge']) || isset($post['badge']) || isset($post['streak']))
                <div class="mx-4 mb-3 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-100 rounded-xl p-3 flex items-center gap-4">
                    @if(isset($post['challenge']))
                        <div class="flex items-center gap-1.5">
                            <span class="text-yellow-500 text-xs">⭐</span>
                            <span class="text-xs font-bold text-gray-700">+{{ $post['points'] }} pts</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-green-500 text-xs">🌿</span>
                            <span class="text-xs text-gray-600">{{ $post['co2'] }}kg CO₂ saved</span>
                        </div>
                        <span class="text-xs text-gray-400 truncate">{{ $post['challenge'] }}</span>
                    @endif
                    @if(isset($post['badge']))
                        <span class="text-lg">🏅</span>
                        <span class="text-xs font-bold text-yellow-700">{{ $post['badge'] }}</span>
                        <span class="text-xs text-gray-600">+{{ $post['xp'] }} XP</span>
                    @endif
                    @if(isset($post['streak']))
                        <span class="animate-flame">🔥</span>
                        <span class="text-xs font-bold text-orange-700">{{ $post['streak'] }}-day streak!</span>
                    @endif
                </div>
            @endif

            <!-- Actions -->
            <div class="px-4 py-3 border-t border-gray-50 flex items-center gap-1">
                <button class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-sm font-medium transition-all {{ $post['liked'] ? 'bg-red-50 text-red-500' : 'text-gray-500 hover:bg-gray-50 hover:text-red-400' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="{{ $post['liked'] ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                    <span class="text-xs">{{ $post['likes'] }}</span>
                </button>
                <button class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-sm text-gray-500 hover:bg-gray-50 hover:text-blue-400 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    <span class="text-xs">{{ $post['comments'] }}</span>
                </button>
                <button class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-sm text-gray-500 hover:bg-gray-50 hover:text-green-500 transition-all ml-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" x2="15.42" y1="13.51" y2="17.49"/><line x1="15.41" x2="8.59" y1="6.51" y2="10.49"/></svg>
                    <span class="text-xs hidden sm:inline">Share</span>
                </button>
            </div>
        </div>
    @endforeach

    <!-- Load more -->
    <button class="w-full py-3 bg-white border border-gray-200 rounded-2xl text-sm font-semibold text-gray-600 hover:bg-green-50 hover:border-green-300 hover:text-green-700 transition-all">
        Load more posts...
    </button>
</div>
@endsection
