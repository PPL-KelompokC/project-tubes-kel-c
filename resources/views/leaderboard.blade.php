@extends('layouts.app')

@section('title', 'Leaderboard - TerraVerde')

@section('content')
@php
    $allUsers = \App\Models\User::orderBy('points', 'desc')->get()->map(function($u) {
        return [
            'id' => $u->id,
            'name' => $u->name,
            'username' => '@' . strtolower(str_replace(' ', '', $u->name)),
            'avatar' => $u->avatar,
            'points' => $u->points,
            'streak' => $u->streak,
            'carbonSaved' => $u->carbon_saved,
            'challengesCompleted' => $u->challenges_completed,
            'location' => $u->location ?? 'Not set',
            'level' => floor($u->points / 1000) + 1,
            'change' => 'same',
            'isCurrentUser' => $u->id === Auth::id()
        ];
    });

    $tab = request('tab', 'weekly');
    $search = request('search');

    $filteredUsers = collect($allUsers)->filter(function($u) use ($search) {
        if ($search && stripos($u['name'], $search) === false && stripos($u['username'], $search) === false) return false;
        return true;
    });

    $top3 = $filteredUsers->take(3)->values();
    $rest = $filteredUsers->slice(3)->values();

    // Podium order: 2nd, 1st, 3rd
    $podiumOrder = [];
    if ($top3->count() >= 2) $podiumOrder[] = ['user' => $top3[1], 'rank' => 2, 'height' => 'h-20', 'ringColor' => 'ring-gray-300'];
    if ($top3->count() >= 1) $podiumOrder[] = ['user' => $top3[0], 'rank' => 1, 'height' => 'h-28', 'ringColor' => 'ring-yellow-300'];
    if ($top3->count() >= 3) $podiumOrder[] = ['user' => $top3[2], 'rank' => 3, 'height' => 'h-16', 'ringColor' => 'ring-orange-300'];
@endphp

<div class="p-4 lg:p-6 max-w-3xl mx-auto space-y-6">
    <!-- Tabs -->
    <div class="flex gap-1 bg-gray-100 p-1 rounded-xl">
        @foreach(['weekly' => 'This Week', 'monthly' => 'This Month', 'alltime' => 'All Time'] as $t => $label)
            <a href="{{ route('leaderboard', ['tab' => $t]) }}" class="flex-1 py-2 rounded-lg text-sm font-semibold text-center transition-all {{ $tab === $t ? 'bg-white text-green-700 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <!-- Top 3 Podium -->
    @if($top3->count() >= 1)
        <div class="bg-gradient-to-b from-green-600 to-emerald-700 rounded-3xl p-6 eco-pattern animate-bounce-in">
            <div class="text-center mb-6">
                <h2 class="text-white font-bold text-lg">Top Champions</h2>
                <p class="text-green-200 text-xs mt-1">Week of March 17, 2024</p>
            </div>

            <!-- Podium -->
            <div class="flex items-end justify-center gap-4">
                @foreach($podiumOrder as $item)
                    @php $user = $item['user']; @endphp
                    <div class="flex flex-col items-center gap-2">
                        <!-- Avatar -->
                        <div class="relative {{ $item['rank'] === 1 ? 'animate-float' : '' }}">
                            @if($user['avatar'])
                                <img src="{{ $user['avatar'] }}" alt="{{ $user['name'] }}" class="rounded-2xl object-cover ring-4 {{ $item['rank'] === 1 ? 'w-16 h-16 ring-yellow-300' : 'w-12 h-12 ring-white/50' }}" />
                            @else
                                <div class="rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-white font-bold ring-4 {{ $item['rank'] === 1 ? 'w-16 h-16 text-xl ring-yellow-300' : 'w-12 h-12 text-base ring-white/50' }}">
                                    {{ substr($user['name'], 0, 1) }}
                                </div>
                            @endif
                            <div class="absolute -top-2 -right-2 w-6 h-6 rounded-full flex items-center justify-center text-xs font-black {{ $item['rank'] === 1 ? 'bg-yellow-400 text-yellow-900' : ($item['rank'] === 2 ? 'bg-gray-300 text-gray-700' : 'bg-orange-400 text-white') }}">
                                {{ $item['rank'] }}
                            </div>
                            @if($user['isCurrentUser'] ?? false)
                                <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 bg-green-400 text-white text-[9px] font-bold px-2 py-0.5 rounded-full whitespace-nowrap">YOU</div>
                            @endif
                        </div>

                        <!-- Name -->
                        <div class="text-center">
                            <p class="text-white text-xs font-bold">{{ explode(' ', $user['name'])[0] }}</p>
                            <p class="text-green-200 text-[10px]">{{ number_format($user['points']) }} pts</p>
                        </div>

                        <!-- Podium block -->
                        <div class="{{ $item['height'] }} w-20 {{ $item['rank'] === 1 ? 'bg-yellow-400/30' : 'bg-white/15' }} rounded-t-xl backdrop-blur-sm flex items-center justify-center">
                            <span class="text-white font-black text-2xl">#{{ $item['rank'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Search -->
    <form action="{{ route('leaderboard') }}" method="GET" class="relative">
        <input type="hidden" name="tab" value="{{ $tab }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
        <input
            name="search"
            value="{{ $search }}"
            placeholder="Search users..."
            class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-300 shadow-sm"
            onchange="this.form.submit()"
        />
    </form>

    <!-- Full Rankings -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
            <p class="text-sm font-bold text-gray-900">Full Rankings</p>
            <p class="text-xs text-gray-500">{{ count($allUsers) }} participants</p>
        </div>

        <div class="divide-y divide-gray-50">
            @foreach($filteredUsers as $i => $user)
                @php $rank = $i + 1; @endphp
                <div class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors {{ ($user['isCurrentUser'] ?? false) ? 'bg-green-50 border-l-4 border-l-green-500' : '' }} animate-count-in" style="animation-delay: {{ $i * 0.05 }}s">
                    <!-- Rank -->
                    <div class="w-8 flex-shrink-0 text-center">
                        @if($rank <= 3)
                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black flex-shrink-0 {{ $rank === 1 ? 'bg-yellow-400 text-yellow-900' : ($rank === 2 ? 'bg-gray-300 text-gray-700' : 'bg-orange-400 text-white') }}">
                                {{ $rank }}
                            </div>
                        @else
                            <span class="text-sm font-bold {{ ($user['isCurrentUser'] ?? false) ? 'text-green-700' : 'text-gray-400' }}">#{{ $rank }}</span>
                        @endif
                    </div>

                    <!-- Avatar -->
                    @if($user['avatar'])
                        <img src="{{ $user['avatar'] }}" alt="{{ $user['name'] }}" class="w-10 h-10 rounded-xl object-cover flex-shrink-0" />
                    @else
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                            {{ substr($user['name'], 0, 1) }}
                        </div>
                    @endif

                    <!-- Info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <p class="text-sm font-bold truncate {{ ($user['isCurrentUser'] ?? false) ? 'text-green-800' : 'text-gray-900' }}">
                                {{ $user['name'] }}
                                @if($user['isCurrentUser'] ?? false)
                                    <span class="ml-1.5 text-xs bg-green-100 text-green-700 px-1.5 py-0.5 rounded-full font-medium">You</span>
                                @endif
                            </p>
                            @if($user['change'] === 'up')
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-500 flex-shrink-0"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
                            @elseif($user['change'] === 'down')
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-500 flex-shrink-0"><polyline points="22 17 13.5 8.5 8.5 13.5 2 7"/><polyline points="16 17 22 17 22 11"/></svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400 flex-shrink-0"><line x1="5" x2="19" y1="12" y2="12"/></svg>
                            @endif
                        </div>
                        <div class="flex items-center gap-3 mt-0.5">
                            <span class="text-[10px] text-gray-500 flex items-center gap-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-400"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg> {{ $user['carbonSaved'] }}kg CO₂
                            </span>
                            <span class="text-[10px] text-gray-500">{{ $user['challengesCompleted'] }} challenges</span>
                            <span class="text-[10px] text-gray-500 hidden sm:inline">{{ $user['location'] }}</span>
                        </div>
                    </div>

                    <!-- Right side -->
                    <div class="flex flex-col items-end gap-1 flex-shrink-0">
                        <div class="flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor" class="text-yellow-500"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            <span class="text-sm font-bold text-gray-900">{{ number_format($user['points']) }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="currentColor" class="text-orange-400"><path d="M12 2c0 0-5.5 5-5.5 10.5A5.5 5.5 0 0 0 12 18a5.5 5.5 0 0 0 5.5-5.5C17.5 7 12 2 12 2Z"/></svg>
                            <span class="text-[10px] font-semibold text-orange-500">{{ $user['streak'] }}d</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Your rank callout -->
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-4 flex items-center gap-4">
        <div class="w-12 h-12 bg-green-600 rounded-2xl flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
        </div>
        <div class="flex-1">
            <p class="text-sm font-bold text-green-900">Your Current Rank: #4</p>
            <p class="text-xs text-green-700">You're only 1,050 pts away from 3rd place!</p>
        </div>
        <div class="text-right">
            <p class="text-lg font-black text-green-700">8,750</p>
            <p class="text-[10px] text-green-600">total points</p>
        </div>
    </div>
</div>
@endsection
