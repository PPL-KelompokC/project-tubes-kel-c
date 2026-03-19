@extends('layouts.app')

@section('title', 'Personal Stats - EcoChallenge')

@section('content')
@php
    $user = Auth::user();
    $currentUser = [
        'totalPoints' => $user->points,
        'carbonSaved' => $user->carbon_saved,
        'challengesCompleted' => $user->challenges_completed,
        'streak' => $user->streak,
    ];

    $weeklyData = [
        ['day' => 'Mon', 'points' => 50],
        ['day' => 'Tue', 'points' => 40],
        ['day' => 'Wed', 'points' => 80],
        ['day' => 'Thu', 'points' => 45],
        ['day' => 'Fri', 'points' => 110],
        ['day' => 'Sat', 'points' => 130],
        ['day' => 'Sun', 'points' => 90],
    ];

    $radarData = [
        ['subject' => 'Transport', 'value' => 78],
        ['subject' => 'Food', 'value' => 65],
        ['subject' => 'Waste', 'value' => 55],
        ['subject' => 'Energy', 'value' => 70],
        ['subject' => 'Water', 'value' => 40],
        ['subject' => 'Nature', 'value' => 85],
    ];

    $monthlyData = [
        ['month' => 'Oct', 'co2' => 28.4],
        ['month' => 'Nov', 'co2' => 34.1],
        ['month' => 'Dec', 'co2' => 29.7],
        ['month' => 'Jan', 'co2' => 38.5],
        ['month' => 'Feb', 'co2' => 42.3],
        ['month' => 'Mar', 'co2' => 47.8],
    ];

    $challengeHistory = [
        ['id' => 1, 'title' => 'Bike to Work', 'date' => 'Mar 17', 'points' => 50, 'co2' => 2.3, 'category' => 'transport'],
        ['id' => 2, 'title' => 'Zero Waste Lunch', 'date' => 'Mar 17', 'points' => 30, 'co2' => 1.1, 'category' => 'food'],
        ['id' => 3, 'title' => 'Recycling Sort', 'date' => 'Mar 16', 'points' => 20, 'co2' => 0.8, 'category' => 'waste'],
        ['id' => 4, 'title' => '5-Min Cold Shower', 'date' => 'Mar 15', 'points' => 25, 'co2' => 0.5, 'category' => 'water'],
        ['id' => 5, 'title' => 'Beach Cleanup', 'date' => 'Mar 14', 'points' => 70, 'co2' => 1.8, 'category' => 'nature'],
    ];

    $categoryColors = [
        'transport' => '#3b82f6',
        'food' => '#22c55e',
        'waste' => '#f97316',
        'energy' => '#eab308',
        'water' => '#06b6d4',
        'nature' => '#10b981',
    ];

    $streakCalendar = [];
    for ($i = 1; $i <= 35; $i++) {
        if ($i <= 23) $streakCalendar[] = ['day' => $i, 'status' => 'completed'];
        elseif ($i === 24) $streakCalendar[] = ['day' => $i, 'status' => 'today'];
        else $streakCalendar[] = ['day' => $i, 'status' => 'future'];
    }
@endphp

<div class="p-4 lg:p-6 max-w-6xl mx-auto space-y-6">
    <!-- Overview stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        @php
            $statCards2 = [
                ['label' => 'Total Points', 'value' => number_format($currentUser['totalPoints']), 'change' => '+1,120 this month', 'positive' => true, 'color' => 'bg-yellow-50 border-yellow-100', 'iconColor' => 'text-yellow-500', 'svg' => '<path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/>'],
                ['label' => 'CO₂ Saved', 'value' => $currentUser['carbonSaved'] . 'kg', 'change' => '+12.3 this month', 'positive' => true, 'color' => 'bg-green-50 border-green-100', 'iconColor' => 'text-green-500', 'svg' => '<path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>'],
                ['label' => 'Challenges', 'value' => $currentUser['challengesCompleted'], 'change' => '+14 this month', 'positive' => true, 'color' => 'bg-blue-50 border-blue-100', 'iconColor' => 'text-blue-500', 'svg' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>'],
                ['label' => 'Current Streak', 'value' => $currentUser['streak'] . 'd', 'change' => 'Personal best: 31d', 'positive' => false, 'color' => 'bg-orange-50 border-orange-100', 'iconColor' => 'text-orange-400', 'svg' => '<path d="M12 2c0 0-5.5 5-5.5 10.5A5.5 5.5 0 0 0 12 18a5.5 5.5 0 0 0 5.5-5.5C17.5 7 12 2 12 2Z"/>'],
            ];
        @endphp
        @foreach($statCards2 as $i => $stat)
            <div class="{{ $stat['color'] }} border rounded-2xl p-4 animate-count-in" style="animation-delay: {{ $i * 0.1 }}s">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mb-2 {{ $stat['iconColor'] }}">{!! $stat['svg'] !!}</svg>
                <p class="text-xl font-black">{{ $stat['value'] }}</p>
                <p class="text-xs text-gray-600 font-medium mt-0.5">{{ $stat['label'] }}</p>
                <p class="text-[10px] mt-1 font-medium {{ $stat['positive'] ? 'text-green-600' : 'text-gray-500' }}">{{ $stat['change'] }}</p>
            </div>
        @endforeach
    </div>

    <!-- Charts row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <!-- Points over time -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-sm font-bold text-gray-900 mb-4">Points Earned — Weekly</h3>
            <div class="h-44 flex items-end justify-between gap-1">
                @php $maxPoints = collect($weeklyData)->max('points') ?: 1; @endphp
                @foreach($weeklyData as $day)
                    @php 
                        $height = round(($day['points'] / $maxPoints) * 100);
                    @endphp
                    <div class="flex-1 flex flex-col items-center gap-2 group relative">
                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block bg-gray-900 text-white text-[10px] px-2 py-1 rounded-lg z-10">
                            {{ $day['points'] }}pts
                        </div>
                        <div class="w-full bg-green-100 rounded-t-lg transition-all hover:bg-green-500" style="height: {{ $height }}%"></div>
                        <span class="text-[10px] text-gray-400 font-medium">{{ $day['day'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Category balance -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-sm font-bold text-gray-900 mb-4">Challenge Category Balance</h3>
            <div class="space-y-3">
                @foreach($radarData as $cat)
                    <div class="space-y-1">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-600 font-medium">{{ $cat['subject'] }}</span>
                            <span class="text-gray-900 font-bold">{{ $cat['value'] }}%</span>
                        </div>
                        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-green-500 rounded-full" style="width: {{ $cat['value'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- CO2 trend + Streak calendar -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <!-- CO2 monthly trend -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-sm font-bold text-gray-900 mb-4">CO₂ Savings — Monthly Trend</h3>
            <div class="h-44 flex items-end justify-between gap-2">
                @php $maxCo2Monthly = collect($monthlyData)->max('co2') ?: 1; @endphp
                @foreach($monthlyData as $month)
                    @php 
                        $height = round(($month['co2'] / $maxCo2Monthly) * 100);
                    @endphp
                    <div class="flex-1 flex flex-col items-center gap-2 group relative">
                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block bg-gray-900 text-white text-[10px] px-2 py-1 rounded-lg z-10">
                            {{ $month['co2'] }}kg
                        </div>
                        <div class="w-full bg-green-500/20 rounded-t-lg transition-all hover:bg-green-500 border-t-2 border-green-500" style="height: {{ $height }}%"></div>
                        <span class="text-[10px] text-gray-400 font-medium">{{ $month['month'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Streak calendar -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-2 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" class="text-orange-400"><path d="M12 2c0 0-5.5 5-5.5 10.5A5.5 5.5 0 0 0 12 18a5.5 5.5 0 0 0 5.5-5.5C17.5 7 12 2 12 2Z"/></svg>
                <h3 class="text-sm font-bold text-gray-900">{{ $currentUser['streak'] }}-Day Streak</h3>
            </div>
            <div class="grid grid-cols-7 gap-1.5">
                @foreach(['M','T','W','T','F','S','S'] as $d)
                    <div class="text-[9px] text-center text-gray-400 font-semibold">{{ $d }}</div>
                @endforeach
                @foreach($streakCalendar as $day)
                    <div
                        class="aspect-square rounded-md flex items-center justify-center text-[9px] font-bold transition-all {{ $day['status'] === 'completed' ? 'bg-green-500 text-white' : ($day['status'] === 'today' ? 'bg-orange-400 text-white ring-2 ring-orange-300' : 'bg-gray-100 text-gray-300') }}"
                    >
                        {{ $day['day'] }}
                    </div>
                @endforeach
            </div>
            <div class="mt-3 flex items-center gap-3 text-[10px] text-gray-500">
                <div class="flex items-center gap-1"><div class="w-2.5 h-2.5 rounded-sm bg-green-500"></div><span>Done</span></div>
                <div class="flex items-center gap-1"><div class="w-2.5 h-2.5 rounded-sm bg-orange-400"></div><span>Today</span></div>
                <div class="flex items-center gap-1"><div class="w-2.5 h-2.5 rounded-sm bg-gray-200"></div><span>Future</span></div>
            </div>
        </div>
    </div>

    <!-- Challenge history timeline -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <h3 class="text-sm font-bold text-gray-900 mb-4">Challenge History</h3>
        <div class="space-y-3 relative">
            <div class="absolute left-5 top-0 bottom-0 w-0.5 bg-gray-100"></div>
            @foreach($challengeHistory as $i => $ch)
                <div class="flex items-start gap-4 pl-12 relative animate-count-in" style="animation-delay: {{ $i * 0.05 }}s">
                    <div class="absolute left-3.5 top-3 w-3 h-3 rounded-full border-2 border-white shadow-sm" style="background-color: {{ $categoryColors[$ch['category']] ?? '#22c55e' }}"></div>
                    <div class="flex-1 bg-gray-50 rounded-xl p-3 flex items-center justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $ch['title'] }}</p>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-[10px] text-gray-500 capitalize">{{ $ch['category'] }}</span>
                                <span class="text-[10px] text-gray-400">·</span>
                                <span class="text-[10px] text-gray-500">{{ $ch['date'] }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 flex-shrink-0 text-right">
                            <div>
                                <p class="text-sm font-bold text-green-700">+{{ $ch['points'] }} pts</p>
                                <p class="text-[10px] text-gray-500">{{ $ch['co2'] }}kg CO₂</p>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-green-500"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
