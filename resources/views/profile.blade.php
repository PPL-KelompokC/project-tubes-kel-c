@extends('layouts.app')

@section('title', 'My Profile - TerraVerde')

@section('content')
@php
    $user = Auth::user();
    $currentUser = [
        'name' => $user->name,
        'username' => '@' . strtolower(str_replace(' ', '', $user->name)),
        'avatar' => $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=059669&color=fff',
        'location' => $user->location ?? 'Not set',
        'bio' => 'Climate activist | SDG advocate | Making small changes for a big impact every single day.',
        'level' => floor($user->points / 1000) + 1,
        'xp' => $user->points % 1000,
        'xpToNextLevel' => 1000,
        'totalPoints' => $user->points,
        'streak' => $user->streak,
        'carbonSaved' => $user->carbon_saved,
        'challengesCompleted' => $user->challenges_completed,
        'rank' => \App\Models\User::where('points', '>', $user->points)->count() + 1,
        'joinedDate' => $user->created_at->format('M Y'),
    ];

    $unlockedBadges = collect([]); // Placeholder for future badge system

    $recentCompletions = $user->submissions()->where('status', 'verified')->latest()->take(3)->get()->map(function($s) {
        return ['id' => $s->id, 'title' => $s->challenge->title, 'category' => $s->challenge->category, 'co2Saved' => $s->challenge->co2_saved, 'points' => $s->points_awarded, 'emoji' => '🎯'];
    });

    $myPosts = $user->feeds()->latest()->take(3)->get();

    $rarityColors = [
        'common' => ['bg' => 'bg-gray-50', 'text' => 'text-gray-600', 'border' => 'border-gray-200'],
        'rare' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200'],
        'epic' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'border' => 'border-purple-200'],
        'legendary' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-700', 'border' => 'border-yellow-200'],
    ];

    $progressPct = round(($currentUser['xp'] / $currentUser['xpToNextLevel']) * 100);
@endphp

<div class="p-4 lg:p-6 max-w-4xl mx-auto space-y-6">
<!-- Success and Error Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-2xl p-4 flex items-center gap-3 animate-bounce-in">
            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    @endif
    @error('avatar')
        <div class="bg-red-50 border border-red-200 rounded-2xl p-4 flex items-center gap-3 animate-bounce-in">
            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            <p class="text-sm text-red-700 font-medium">{{ $message }}</p>
        </div>
    @enderror

    <!-- Profile card -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden animate-bounce-in">
        <!-- Cover banner -->
        <div class="eco-gradient h-32 relative eco-pattern">
            <div class="absolute -bottom-12 left-6 flex items-end gap-4">
                <div class="relative group cursor-pointer" onclick="document.getElementById('avatarInput').click()">
                    <img
                        src="{{ $currentUser['avatar'] }}"
                        alt="{{ $currentUser['name'] }}"
                        class="w-24 h-24 rounded-2xl object-cover ring-4 ring-white shadow-lg transition-all group-hover:brightness-75"
                    />
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <svg class="w-8 h-8 text-white drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div class="absolute -bottom-1.5 -right-1.5 bg-green-500 text-white text-xs font-bold px-2 py-0.5 rounded-full z-10">
                        Lv.{{ $currentUser['level'] }}
                    </div>
                </div>
            </div>

            <!-- Hidden Form for Avatar Upload -->
            <form id="avatarForm" action="{{ route('profile.avatar.update') }}" method="POST" enctype="multipart/form-data" class="hidden">
                @csrf
                <input type="file" id="avatarInput" name="avatar" accept="image/*" onchange="document.getElementById('avatarForm').submit();" />
            </form>

            <div class="absolute top-4 right-4 flex gap-2">
                <button class="bg-white/20 backdrop-blur-sm text-white px-3 py-1.5 rounded-xl text-xs font-semibold flex items-center gap-1.5 hover:bg-white/30 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" x2="15.42" y1="13.51" y2="17.49"/><line x1="15.41" x2="8.59" y1="6.51" y2="10.49"/></svg>
                    Share
                </button>
                <button onclick="document.getElementById('avatarInput').click()" class="bg-white/20 backdrop-blur-sm text-white px-3 py-1.5 rounded-xl text-xs font-semibold flex items-center gap-1.5 hover:bg-white/30 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                    Edit Photo
                </button>
            </div>
        </div>

        <!-- Profile info -->
        <div class="pt-16 pb-5 px-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">{{ $currentUser['name'] }}</h1>
                    <p class="text-sm text-gray-500">{{ $currentUser['username'] }}</p>
                    <div class="flex items-center gap-3 mt-2 text-xs text-gray-500">
                        <span class="flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>{{ $currentUser['location'] }}</span>
                        <span class="flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>Joined {{ $currentUser['joinedDate'] }}</span>
                    </div>
                </div>
                <!-- Streak badge -->
                <div class="flex items-center gap-2 bg-orange-50 border border-orange-100 rounded-2xl px-4 py-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="currentColor" class="text-orange-400"><path d="M12 2c0 0-5.5 5-5.5 10.5A5.5 5.5 0 0 0 12 18a5.5 5.5 0 0 0 5.5-5.5C17.5 7 12 2 12 2Z"/></svg>
                    <div>
                        <p class="text-xl font-black text-orange-600">{{ $currentUser['streak'] }}</p>
                        <p class="text-[10px] text-orange-500 font-medium leading-tight">day streak</p>
                    </div>
                </div>
            </div>

            <!-- Bio -->
            <div class="mt-3">
                <p class="text-sm text-gray-600">{{ $currentUser['bio'] }}</p>
            </div>

            <!-- XP Progress -->
            <div class="mt-4 bg-gray-50 rounded-2xl p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-gray-700">Level {{ $currentUser['level'] }} → {{ $currentUser['level'] + 1 }}</span>
                    <span class="text-xs text-green-600 font-bold">{{ $currentUser['xp'] }} / {{ $currentUser['xpToNextLevel'] }} XP</span>
                </div>
                <div class="h-3 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-green-400 to-emerald-500 rounded-full animate-xp-glow" style="width: {{ $progressPct }}%"></div>
                </div>
                <p class="text-[10px] text-gray-500 mt-1.5">{{ $currentUser['xpToNextLevel'] - $currentUser['xp'] }} XP to next level</p>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        @php
            $profileStatCards = [
                ['label' => 'Total Points', 'value' => number_format($currentUser['totalPoints']), 'sub' => 'All time', 'color' => 'bg-yellow-50 border-yellow-100', 'iconColor' => 'text-yellow-500', 'svg' => '<path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/>'],
                ['label' => 'CO₂ Saved', 'value' => $currentUser['carbonSaved'], 'sub' => 'kilograms', 'color' => 'bg-green-50 border-green-100', 'iconColor' => 'text-green-500', 'svg' => '<path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>'],
                ['label' => 'Challenges', 'value' => $currentUser['challengesCompleted'], 'sub' => 'completed', 'color' => 'bg-blue-50 border-blue-100', 'iconColor' => 'text-blue-500', 'svg' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>'],
                ['label' => 'Global Rank', 'value' => '#' . $currentUser['rank'], 'sub' => 'worldwide', 'color' => 'bg-purple-50 border-purple-100', 'iconColor' => 'text-purple-500', 'svg' => '<path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/>'],
            ];
        @endphp
        @foreach($profileStatCards as $i => $stat)
            <div class="{{ $stat['color'] }} border rounded-2xl p-4 text-center animate-count-in" style="animation-delay: {{ $i * 0.1 }}s">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-1 {{ $stat['iconColor'] }}">{!! $stat['svg'] !!}</svg>
                <p class="text-xl font-black {{ $stat['iconColor'] }}">{!! $stat['value'] !!}</p>
                <p class="text-xs text-gray-500">{{ $stat['label'] }}</p>
                <p class="text-[10px] text-gray-400">{{ $stat['sub'] }}</p>
            </div>
        @endforeach
    </div>

    <!-- Badges row -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-gray-900">Recent Badges</h3>
            <a href="{{ route('badges') }}" class="text-xs text-green-600 font-semibold hover:text-green-700">View all →</a>
        </div>
        <div class="flex gap-3 overflow-x-auto pb-2">
            @foreach($unlockedBadges as $badge)
                @php $rc = $rarityColors[$badge['rarity']] ?? $rarityColors['common']; @endphp
                <div class="flex-shrink-0 {{ $rc['bg'] }} border {{ $rc['border'] }} rounded-2xl p-3 text-center w-20">
                    <div class="text-2xl mb-1">{{ $badge['emoji'] }}</div>
                    <p class="text-[10px] font-semibold text-gray-700 line-clamp-2 leading-tight">{{ $badge['name'] }}</p>
                </div>
            @endforeach
            <a href="{{ route('badges') }}" class="flex-shrink-0 bg-gray-50 border border-gray-200 rounded-2xl p-3 text-center w-20 flex flex-col items-center justify-center gap-1 hover:bg-gray-100 transition-colors">
                <span class="text-gray-400 text-lg">+10</span>
                <p class="text-[10px] text-gray-500 font-medium">to unlock</p>
            </a>
        </div>
    </div>

    <!-- Completed challenges -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-gray-900">Recent Completions</h3>
            <a href="{{ route('stats') }}" class="text-xs text-green-600 font-semibold hover:text-green-700">Full history →</a>
        </div>
        <div class="space-y-2.5">
            @foreach($recentCompletions as $ch)
                <div class="flex items-center gap-3 p-3 bg-green-50 border border-green-100 rounded-xl animate-count-in">
                    <div class="w-9 h-9 rounded-xl bg-green-200 flex items-center justify-center text-lg flex-shrink-0">
                        {{ $ch['emoji'] }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-green-900 truncate">{{ $ch['title'] }}</p>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-xs text-green-700">{{ $ch['co2Saved'] }}kg CO₂ saved</span>
                            <span class="text-xs text-gray-400">·</span>
                            <span class="text-xs text-gray-500">+{{ $ch['points'] }} pts</span>
                        </div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-green-500 flex-shrink-0"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Recent activity -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <h3 class="text-sm font-bold text-gray-900 mb-4">My Recent Posts</h3>
        <div class="space-y-3">
            @forelse($myPosts as $post)
                <div class="flex gap-3">
                    <img src="{{ $currentUser['avatar'] }}" alt="" class="w-8 h-8 rounded-full object-cover flex-shrink-0" />
                    <div class="flex-1 bg-gray-50 rounded-xl p-3">
                        <p class="text-xs text-gray-700">{{ $post->caption }}</p>
                        
                        @if($post->media && count($post->media) > 0)
                            <div class="mt-2 grid grid-cols-2 gap-1">
                                @foreach(array_slice($post->media, 0, 2) as $media)
                                    @php
                                        $url = is_array($media) ? ($media['url'] ?? $media) : $media;
                                        $type = is_array($media) ? ($media['type'] ?? 'image') : 'image';
                                    @endphp
                                    @if($type === 'video')
                                        <video class="w-full h-20 object-cover rounded-lg bg-gray-200">
                                            <source src="{{ $url }}" />
                                        </video>
                                    @else
                                        <img src="{{ $url }}" class="w-full h-20 object-cover rounded-lg border border-gray-200" />
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        <div class="flex items-center gap-3 mt-2 text-[10px] text-gray-400">
                            <span class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="currentColor" class="text-red-400"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                                {{ $post->likes_count }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-400"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                {{ $post->comments_count }}
                            </span>
                            <span>{{ $post->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-4 bg-gray-50 rounded-xl border border-gray-100 border-dashed">
                    <p class="text-xs text-gray-500">No posts yet. Go to Activity Feed to share your eco action!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
