@extends('layouts.app')

@section('title', 'Daily Dashboard - EcoChallenge')

@section('content')
@php
    $currentUser = [
        'name' => $user->name,
        'streak' => $user->streak,
        'totalPoints' => $user->points,
        'carbonSaved' => $user->carbon_saved,
        'challengesCompleted' => $user->challenges_completed,
        'rank' => $rank,
        'avatar' => $user->avatar
    ];

    $completedToday = $completedToday ?? 0;
    $progressPct = $progressPct ?? 0;

    $categoryConfig = [
        'transport' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'icon' => 'bike'],
        'food'      => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'icon' => 'leaf'],
        'waste'     => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'icon' => 'recycle'],
        'energy'    => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'icon' => 'zap'],
    ];

    $difficultyColor = [
        'easy'   => 'bg-green-100 text-green-700',
        'medium' => 'bg-yellow-100 text-yellow-700',
        'hard'   => 'bg-red-100 text-red-700',
    ];
@endphp

<div class="p-4 lg:p-6 max-w-7xl mx-auto space-y-6">
    <!-- Hero Banner -->
    <div class="rounded-3xl p-6 text-white relative overflow-hidden animate-bounce-in" style="background: linear-gradient(135deg, #15803d 0%, #047857 45%, #0369a1 100%);">
        <!-- Decorative overlay circles -->
        <div class="absolute inset-0 rounded-3xl" style="background-image: radial-gradient(circle at 15% 75%, rgba(52,211,153,0.18) 0%, transparent 55%), radial-gradient(circle at 85% 15%, rgba(56,189,248,0.15) 0%, transparent 55%);"></div>
        <div class="relative z-10">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-green-200 text-sm font-semibold mb-1 drop-shadow">Good morning!</p>
                    <h1 class="text-2xl font-bold mb-1 text-white drop-shadow-md">Hey, {{ explode(' ', $currentUser['name'])[0] }}!</h1>
                    <p class="text-green-100 text-sm drop-shadow">You're making a difference. Keep going!</p>
                </div>
                <div class="flex flex-col items-center bg-white/20 backdrop-blur-sm rounded-2xl p-3 min-w-16 border border-white/10 shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="text-orange-300 mb-1"><path d="M12 2c0 0-5.5 5-5.5 10.5A5.5 5.5 0 0 0 12 18a5.5 5.5 0 0 0 5.5-5.5C17.5 7 12 2 12 2Z"/></svg>
                    <span class="text-2xl font-black text-white">{{ $currentUser['streak'] }}</span>
                    <span class="text-xs text-green-200 font-medium">day streak</span>
                </div>
            </div>

            <!-- Today's progress -->
            <div class="mt-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-semibold text-green-100">Today's Progress</span>
                    <span class="text-sm font-bold text-white">{{ $completedToday }}/{{ count($todaysChallenges) }} challenges</span>
                </div>
                <div class="h-2.5 bg-white/20 rounded-full overflow-hidden">
                    <div class="h-full bg-white rounded-full animate-progress" style="--target-width: {{ $progressPct }}%; width: {{ $progressPct }}%"></div>
                </div>
            </div>
        </div>
        <div class="absolute -right-8 -top-8 w-32 h-32 rounded-full bg-white/5"></div>
        <div class="absolute -right-4 top-16 w-20 h-20 rounded-full bg-white/5"></div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        @php
            $statCards = [
                ['label' => 'Total Points', 'value' => number_format($currentUser['totalPoints']), 'color' => 'from-yellow-50 to-amber-50', 'border' => 'border-yellow-100', 'text' => 'text-yellow-700', 'iconColor' => 'text-yellow-500', 'svg' => '<path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/>'],
                ['label' => 'CO₂ Saved', 'value' => $currentUser['carbonSaved'] . ' kg', 'color' => 'from-green-50 to-emerald-50', 'border' => 'border-green-100', 'text' => 'text-green-700', 'iconColor' => 'text-green-500', 'svg' => '<path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>'],
                ['label' => 'Completed', 'value' => $currentUser['challengesCompleted'], 'color' => 'from-blue-50 to-indigo-50', 'border' => 'border-blue-100', 'text' => 'text-blue-700', 'iconColor' => 'text-blue-500', 'svg' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>'],
                ['label' => 'Global Rank', 'value' => '#' . $currentUser['rank'], 'color' => 'from-purple-50 to-pink-50', 'border' => 'border-purple-100', 'text' => 'text-purple-700', 'iconColor' => 'text-purple-500', 'svg' => '<path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/>'],
            ];
        @endphp
        @foreach($statCards as $i => $stat)
            <div class="bg-gradient-to-br {{ $stat['color'] }} rounded-2xl p-4 border {{ $stat['border'] }} animate-count-in" style="animation-delay: {{ $i * 0.1 }}s">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mb-2 {{ $stat['iconColor'] }}">{!! $stat['svg'] !!}</svg>
                <p class="text-xl font-black {{ $stat['text'] }}">{{ $stat['value'] }}</p>
                <p class="text-xs text-gray-500 font-medium mt-0.5">{{ $stat['label'] }}</p>
            </div>
        @endforeach
    </div>

    <!-- Today's Challenges -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-base font-bold text-gray-900">Today's Challenges</h2>
                <p class="text-xs text-gray-500">Complete all 3 to earn a bonus 50 pts!</p>
            </div>
            <a href="{{ route('challenges') }}" class="flex items-center gap-1 text-xs text-green-600 font-semibold hover:text-green-700">
                View all <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($todaysChallenges as $i => $challenge)
                @php
                    $status      = $challenge['status'];
                    $isVerified  = $status === 'verified';
                    $isPending   = in_array($status, ['pending_ai', 'manual_review']);
                    $isRejected  = $status === 'rejected';
                    $isSubmitted = $challenge['submitted'];
                    $cat         = $categoryConfig[strtolower($challenge['category'])] ?? $categoryConfig['food'];
                @endphp
                <div class="bg-white rounded-2xl shadow-sm border overflow-hidden card-hover transition-all animate-bounce-in {{ $isVerified ? 'border-green-200 bg-green-50/20' : ($isPending ? 'border-yellow-200 bg-yellow-50/10' : 'border-gray-100') }}" style="animation-delay: {{ $i * 0.1 }}s">
                    @if($challenge['imageUrl'])
                        <div class="relative h-36 overflow-hidden">
                            <img src="{{ $challenge['imageUrl'] }}" alt="{{ $challenge['title'] }}" class="w-full h-full object-cover" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                            <div class="absolute top-2.5 left-2.5 flex gap-1.5">
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $cat['bg'] }} {{ $cat['text'] }}">
                                    {{ $cat['icon'] }} {{ ucfirst($challenge['category']) }}
                                </span>
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $difficultyColor[strtolower($challenge['difficulty'])] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst($challenge['difficulty']) }}
                                </span>
                            </div>
                            @if($isVerified)
                                <div class="absolute top-2.5 right-2.5 w-7 h-7 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-white"><path d="M20 6 9 17l-5-5"/></svg>
                                </div>
                            @elseif($isPending)
                                <div class="absolute top-2.5 right-2.5 bg-yellow-400 text-yellow-900 text-[10px] font-bold px-2 py-0.5 rounded-full">
                                    Pending
                                </div>
                            @elseif($isRejected)
                                <div class="absolute top-2.5 right-2.5 bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                                    Rejected
                                </div>
                            @endif
                            <div class="absolute bottom-2.5 right-2.5 bg-white/90 backdrop-blur-sm rounded-full px-2.5 py-1 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="currentColor" class="text-yellow-500"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                <span class="text-xs font-bold text-gray-800">{{ $challenge['points'] }} pts</span>
                            </div>
                        </div>
                    @endif
                    <div class="p-4">
                        <h3 class="text-sm font-bold mb-1.5 {{ $isVerified ? 'text-green-800' : 'text-gray-900' }}">{{ $challenge['title'] }}</h3>
                        <p class="text-xs text-gray-500 line-clamp-2 mb-3">{{ $challenge['description'] }}</p>
                        
                        <div class="flex items-center gap-3 mb-3">
                            <div class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-500"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
                                <span class="text-xs text-gray-600 font-medium">{{ $challenge['co2Saved'] }} kg CO₂</span>
                            </div>
                            <div class="flex items-center gap-1 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                <span class="text-xs">{{ number_format($challenge['participants']) }}</span>
                            </div>
                        </div>

                        @if($isVerified)
                            <div class="flex items-center gap-2 bg-green-50 border border-green-200 rounded-xl px-3 py-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-green-600"><path d="M20 6 9 17l-5-5"/></svg>
                                <span class="text-sm font-semibold text-green-700">Verified! +{{ $challenge['points'] }} pts</span>
                            </div>
                        @elseif($isPending)
                            <div class="flex items-center gap-2 bg-yellow-50 border border-yellow-200 rounded-xl px-3 py-2">
                                <svg class="animate-spin h-4 w-4 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                <span class="text-sm font-semibold text-yellow-700">
                                    @if($status === 'pending_ai') AI Verifying… @else Awaiting Admin Review @endif
                                </span>
                            </div>
                        @elseif($isRejected)
                            <a href="{{ route('challenges.submit', $challenge['id']) }}"
                               class="w-full bg-red-100 hover:bg-red-200 text-red-700 text-sm font-semibold py-2.5 rounded-xl transition-all flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                                Retake Photo
                            </a>
                        @else
                            <a href="{{ route('challenges.submit', $challenge['id']) }}"
                               class="w-full bg-green-600 hover:bg-green-700 text-white text-sm font-semibold py-2.5 rounded-xl transition-all active:scale-95 flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                                Mark as Completed
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Bottom grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Mini Leaderboard -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-gray-900">Top Leaders</h3>
                <a href="{{ route('leaderboard') }}" class="text-xs text-green-600 font-semibold hover:text-green-700">See all →</a>
            </div>
            <div class="space-y-3">
                @foreach($topUsers as $i => $leader)
                    @php $isCurrentUser = $leader->id === Auth::id(); @endphp
                    <div class="flex items-center gap-3 p-2 rounded-xl {{ $isCurrentUser ? 'bg-green-50 border border-green-100' : '' }}">
                        <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 {{ $i == 0 ? 'bg-yellow-100 text-yellow-700' : ($i == 1 ? 'bg-gray-100 text-gray-600' : ($i == 2 ? 'bg-orange-100 text-orange-700' : 'bg-gray-50 text-gray-500')) }}">
                            {{ $i + 1 }}
                        </span>
                        @if($leader->avatar)
                            <img src="{{ $leader->avatar }}" alt="{{ $leader->name }}" class="w-8 h-8 rounded-full object-cover flex-shrink-0" />
                        @else
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                {{ substr($leader->name, 0, 1) }}
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold truncate {{ $isCurrentUser ? 'text-green-800' : 'text-gray-800' }}">
                                {{ $leader->name }} {{ $isCurrentUser ? '(You)' : '' }}
                            </p>
                            <p class="text-[10px] text-gray-500">{{ number_format($leader->points) }} pts</p>
                        </div>
                        <div class="flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="currentColor" class="text-orange-400"><path d="M12 2c0 0-5.5 5-5.5 10.5A5.5 5.5 0 0 0 12 18a5.5 5.5 0 0 0 5.5-5.5C17.5 7 12 2 12 2Z"/></svg>
                            <span class="text-[10px] text-orange-500 font-semibold">{{ $leader->streak }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Weekly Activity -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-gray-900">This Week's Activity</h3>
                <a href="{{ route('stats') }}" class="text-xs text-green-600 font-semibold hover:text-green-700">Full stats →</a>
            </div>
            <div class="flex items-end justify-between gap-2 h-28">
                @php $maxCo2 = max(array_column($weeklyData, 'co2')) ?: 1; @endphp
                @foreach($weeklyData as $i => $day)
                    @php 
                        $height = round(($day['co2'] / $maxCo2) * 100);
                        $isToday = strtolower($day['day']) === strtolower(now()->format('D'));
                    @endphp
                    <div class="flex flex-col items-center gap-1.5 flex-1 group">
                        <div class="w-full relative">
                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 hidden group-hover:block bg-gray-900 text-white text-[10px] rounded-lg px-2 py-1 whitespace-nowrap z-10">
                                {{ $day['co2'] }}kg CO₂ · {{ $day['points'] }}pts
                            </div>
                            <div class="rounded-t-lg mx-auto transition-all {{ $isToday ? 'bg-green-500' : 'bg-green-100 hover:bg-green-200' }}" style="height: {{ $height }}%; min-height: 8px; max-height: 80px; width: 100%"></div>
                        </div>
                        <span class="text-[10px] font-medium {{ $isToday ? 'text-green-600' : 'text-gray-400' }}">{{ $day['day'] }}</span>
                    </div>
                @endforeach
            </div>
            <div class="mt-3 pt-3 border-t border-gray-50 flex items-center justify-between text-xs text-gray-500">
                <span>Week total: <strong class="text-green-600">{{ array_sum(array_column($weeklyData, 'co2')) }}kg CO₂</strong> saved</span>
                <span>{{ array_sum(array_column($weeklyData, 'points')) }} pts earned</span>
            </div>
        </div>
    </div>
</div>
@endsection
