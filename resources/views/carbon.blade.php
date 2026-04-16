@extends('layouts.app')

@section('title', 'Carbon Tracker - EcoChallenge')

@section('content')
@php
    $user = Auth::user();
    
    // Total Carbon accumulated from verified daily challenges
    $totalCO2 = (float) \DB::table('challenge_submissions')
        ->where('challenge_submissions.user_id', $user->id)
        ->whereIn('challenge_submissions.status', ['verified', 'pending_admin'])
        ->join('challenges', 'challenge_submissions.challenge_id', '=', 'challenges.id')
        ->sum('challenges.co2_saved');

    $treesEquivalent = floor($totalCO2 / 21.7);
    $drivingMiles = floor($totalCO2 * 2.5);
    $electricDays = floor($totalCO2 * 0.8);

    $viewMode = request('view', 'weekly');
    $graphData = [];

    if ($viewMode === 'monthly') {
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyCo2 = (float) \DB::table('challenge_submissions')
                ->where('challenge_submissions.user_id', $user->id)
                ->whereIn('challenge_submissions.status', ['verified', 'pending_admin'])
                ->whereYear('challenge_submissions.created_at', $date->year)
                ->whereMonth('challenge_submissions.created_at', $date->month)
                ->join('challenges', 'challenge_submissions.challenge_id', '=', 'challenges.id')
                ->sum('challenges.co2_saved');
                
            $graphData[] = [
                'label' => $date->format('M'),
                'co2' => $monthlyCo2
            ];
        }
    } else {
        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $startOfWeek = now()->startOfWeek();
        
        foreach ($days as $i => $dayName) {
            $date = $startOfWeek->copy()->addDays($i);
            $dailyCo2 = (float) \DB::table('challenge_submissions')
                ->where('challenge_submissions.user_id', $user->id)
                ->whereIn('challenge_submissions.status', ['verified', 'pending_admin'])
                ->whereDate('challenge_submissions.created_at', $date->format('Y-m-d'))
                ->join('challenges', 'challenge_submissions.challenge_id', '=', 'challenges.id')
                ->sum('challenges.co2_saved');
                
            $graphData[] = [
                'label' => $dayName,
                'co2' => $dailyCo2
            ];
        }
    }

    // This month vs last month CO2
    $thisMonthCO2 = (float) \DB::table('challenge_submissions')
        ->where('challenge_submissions.user_id', $user->id)
        ->whereIn('challenge_submissions.status', ['verified', 'pending_admin'])
        ->whereYear('challenge_submissions.created_at', now()->year)
        ->whereMonth('challenge_submissions.created_at', now()->month)
        ->join('challenges', 'challenge_submissions.challenge_id', '=', 'challenges.id')
        ->sum('challenges.co2_saved');

    $lastMonthCO2 = (float) \DB::table('challenge_submissions')
        ->where('challenge_submissions.user_id', $user->id)
        ->whereIn('challenge_submissions.status', ['verified', 'pending_admin'])
        ->whereYear('challenge_submissions.created_at', now()->subMonth()->year)
        ->whereMonth('challenge_submissions.created_at', now()->subMonth()->month)
        ->join('challenges', 'challenge_submissions.challenge_id', '=', 'challenges.id')
        ->sum('challenges.co2_saved');

    // Percentage change vs last month
    if ($lastMonthCO2 > 0) {
        $monthlyChangePct = round((($thisMonthCO2 - $lastMonthCO2) / $lastMonthCO2) * 100);
    } elseif ($thisMonthCO2 > 0) {
        $monthlyChangePct = 100; // first time saving this month
    } else {
        $monthlyChangePct = 0;
    }

    $rawCategories = \DB::table('challenge_submissions')
        ->where('challenge_submissions.user_id', $user->id)
        ->whereIn('challenge_submissions.status', ['verified', 'pending_admin'])
        ->join('challenges', 'challenge_submissions.challenge_id', '=', 'challenges.id')
        ->selectRaw('challenges.category, SUM(challenges.co2_saved) as co2')
        ->groupBy('challenges.category')
        ->get();

    $categoryMeta = [
        'transport' => ['color' => '#3b82f6', 'name' => 'Transport'],
        'food'      => ['color' => '#22c55e', 'name' => 'Food'],
        'waste'     => ['color' => '#f97316', 'name' => 'Waste'],
        'energy'    => ['color' => '#eab308', 'name' => 'Energy'],
        'nature'    => ['color' => '#10b981', 'name' => 'Nature'],
        'lifestyle' => ['color' => '#8b5cf6', 'name' => 'Lifestyle'],
        'water'     => ['color' => '#06b6d4', 'name' => 'Water'],
    ];

    $categoryBreakdown = [];
    foreach ($rawCategories as $cat) {
        $c = strtolower($cat->category);
        $meta = $categoryMeta[$c] ?? ['color' => '#94a3b8', 'name' => ucfirst($c)];
        $pct = $totalCO2 > 0 ? round(($cat->co2 / $totalCO2) * 100) : 0;
        
        $categoryBreakdown[] = [
            'name' => $meta['name'],
            'value' => $pct,
            'color' => $meta['color'],
            'co2' => round($cat->co2, 1)
        ];
    }
    
    usort($categoryBreakdown, function($a, $b) {
        return $b['co2'] <=> $a['co2'];
    });

    $milestones = [
        ['label' => 'Eco Rookie (10 kg saved)', 'target' => 10, 'svgPath' => '<path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>', 'color' => 'bg-green-400'],
        ['label' => 'Green Champion (50 kg saved)', 'target' => 50, 'svgPath' => '<path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>', 'color' => 'bg-green-500'],
        ['label' => 'Carbon Crusher (100 kg saved)', 'target' => 100, 'svgPath' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>', 'color' => 'bg-emerald-500'],
        ['label' => 'Earth Guardian (500 kg saved)', 'target' => 500, 'svgPath' => '<path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/>', 'color' => 'bg-blue-500'],
    ];
@endphp

<div class="p-4 lg:p-6 max-w-6xl mx-auto space-y-6">

    <!-- Hero metric -->
    <div class="rounded-3xl p-6 lg:p-8 text-white relative overflow-hidden animate-bounce-in shadow-lg" style="background: linear-gradient(135deg, #15803d 0%, #047857 45%, #0369a1 100%);">
        <!-- Decorative overlay circles -->
        <div class="absolute inset-0 rounded-3xl" style="background-image: radial-gradient(circle at 15% 75%, rgba(52,211,153,0.18) 0%, transparent 55%), radial-gradient(circle at 85% 15%, rgba(56,189,248,0.15) 0%, transparent 55%);"></div>
        <div class="relative z-10 flex flex-col lg:flex-row items-start lg:items-center gap-6">
            <div class="flex-1">
                <p class="text-green-200 text-sm font-medium mb-2">Total Carbon Saved</p>
                <div class="flex items-end gap-2">
                    <span class="text-6xl lg:text-7xl font-black text-white animate-count-in">
                        {{ $totalCO2 }}
                    </span>
                    <span class="text-2xl font-bold text-green-200 mb-2">kg CO₂</span>
                </div>
                <p class="text-green-100 text-sm mt-2">Since joining EcoChallenge • Jan 2024</p>
                <div class="mt-3 bg-white/20 backdrop-blur-sm rounded-xl p-3 inline-flex items-center gap-2">
                    @if($monthlyChangePct >= 0)
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-200"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-300"><polyline points="22 17 13.5 8.5 8.5 13.5 2 7"/><polyline points="16 17 22 17 22 11"/></svg>
                    @endif
                    <span class="text-sm font-semibold text-white">
                        {{ $thisMonthCO2 > 0 ? '+' : '' }}{{ round($thisMonthCO2, 1) }} kg this month
                    </span>
                    @if($lastMonthCO2 > 0 || $thisMonthCO2 > 0)
                        <span class="text-green-200 text-xs">
                            ({{ $monthlyChangePct >= 0 ? '+' : '' }}{{ $monthlyChangePct }}% vs last month)
                        </span>
                    @else
                        <span class="text-green-200 text-xs">(no data yet)</span>
                    @endif
                </div>
            </div>

            <!-- Impact equivalents -->
            <div class="grid grid-cols-3 gap-3 w-full lg:w-auto">
                @foreach([
                    ['svgPath' => '<path d="M17 14c.83-1.071 1.5-2.547 1.5-4.5C18.5 5.686 15.314 3 12 3S5.5 5.686 5.5 9.5c0 1.953.67 3.429 1.5 4.5"/><path d="M12 3v11"/><path d="M9 21h6"/><path d="M12 16v5"/>', 'value' => $treesEquivalent, 'label' => 'Trees absorbing', 'sub' => 'for 1 year'],
                    ['svgPath' => '<rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>', 'value' => $drivingMiles, 'label' => 'Miles of driving', 'sub' => 'avoided'],
                    ['svgPath' => '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>', 'value' => $electricDays, 'label' => 'Days of home', 'sub' => 'electricity'],
                ] as $item)
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl p-3 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white/80 mx-auto mb-1">{!! $item['svgPath'] !!}</svg>
                        <p class="text-xl font-black text-white">{{ $item['value'] }}</p>
                        <p class="text-[10px] text-green-200 leading-tight">{{ $item['label'] }}</p>
                        <p class="text-[10px] text-green-300">{{ $item['sub'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="absolute -right-10 -bottom-10 w-48 h-48 rounded-full bg-white/5"></div>
    </div>

    <!-- Chart section -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-sm font-bold text-gray-900">CO₂ Savings Over Time</h3>
                <p class="text-xs text-gray-500 mt-0.5">Track your daily environmental impact</p>
            </div>
            <div class="flex gap-1 bg-gray-100 p-1 rounded-xl">
                <a href="{{ route('carbon', ['view' => 'weekly']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ request('view', 'weekly') === 'weekly' ? 'bg-white text-green-700 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">Weekly</a>
                <a href="{{ route('carbon', ['view' => 'monthly']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ request('view') === 'monthly' ? 'bg-white text-green-700 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">Monthly</a>
            </div>
        </div>

        <div class="h-56 w-full flex items-end justify-between gap-2">
            @php $maxCo2 = collect($graphData)->max('co2') ?: 1; @endphp
            @foreach($graphData as $point)
                @php 
                    $height = round(($point['co2'] / $maxCo2) * 100);
                @endphp
                <div class="flex-1 flex flex-col items-center gap-2 group relative h-full justify-end">
                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block bg-gray-900 text-white text-[10px] px-2 py-1 rounded-lg z-10 whitespace-nowrap">
                        {{ $point['co2'] }}kg CO₂
                    </div>
                    <div class="w-full bg-green-100 rounded-t-lg transition-all hover:bg-green-500 group-hover:animate-pulse" style="height: {{ $point['co2'] > 0 ? max(10, $height) : 5 }}%"></div>
                    <span class="text-[10px] text-gray-400 font-medium">{{ $point['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Bottom grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <!-- Category Breakdown -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-sm font-bold text-gray-900 mb-4">Savings by Category</h3>
            <div class="space-y-3">
                @foreach($categoryBreakdown as $cat)
                    <div class="space-y-1">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-600 font-medium">{{ $cat['name'] }}</span>
                            <span class="text-gray-900 font-bold">{{ $cat['co2'] }}kg ({{ $cat['value'] }}%)</span>
                        </div>
                        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full" style="background-color: {{ $cat['color'] }}; width: {{ $cat['value'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Impact milestones -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-sm font-bold text-gray-900 mb-4">Impact Milestones</h3>
            <div class="space-y-3">
                @foreach($milestones as $milestone)
                    @php 
                        $pct = min(100, round(($totalCO2 / $milestone['target']) * 100));
                        $done = $totalCO2 >= $milestone['target'];
                    @endphp
                    <div class="p-3 rounded-xl border {{ $done ? 'border-green-200 bg-green-50' : 'border-gray-100 bg-gray-50' }}">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-current flex-shrink-0">{!! $milestone['svgPath'] !!}</svg>
                                <span class="text-xs font-semibold {{ $done ? 'text-green-800' : 'text-gray-700' }}">{{ $milestone['label'] }}</span>
                                @if($done)
                                    <span class="text-[10px] bg-green-200 text-green-800 px-2 py-0.5 rounded-full font-bold">✓ Achieved!</span>
                                @endif
                            </div>
                            <span class="text-xs font-bold text-gray-600">{{ min($totalCO2, $milestone['target']) }}/{{ $milestone['target'] }}kg</span>
                        </div>
                        <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full {{ $milestone['color'] }} rounded-full animate-progress" style="--target-width: {{ $pct }}%; width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
