@extends('layouts.app')

@section('title', 'Admin Panel - EcoChallenge')

@section('content')
@php
    $adminStats = [
        'totalUsers' => 15847,
        'activeToday' => 3241,
        'challengesCompleted' => 48762,
        'totalCO2Saved' => 87432,
        'newUsersThisWeek' => 423,
        'completionRate' => 68.4,
    ];

    $adminUsers = [
        ['id' => 1, 'name' => 'Alex Chen', 'email' => 'alex@example.com', 'points' => 12450, 'status' => 'active', 'challenges' => 156, 'joined' => '2024-01-10', 'flagged' => false],
        ['id' => 2, 'name' => 'Sofia Ramirez', 'email' => 'sofia@example.com', 'points' => 11200, 'status' => 'active', 'challenges' => 134, 'joined' => '2024-01-15', 'flagged' => false],
        ['id' => 3, 'name' => 'James Okafor', 'email' => 'james@example.com', 'points' => 9800, 'status' => 'active', 'challenges' => 112, 'joined' => '2024-01-20', 'flagged' => false],
        ['id' => 5, 'name' => 'Spam Bot 1', 'email' => 'spambot@fake.com', 'points' => 50000, 'status' => 'flagged', 'challenges' => 999, 'joined' => '2024-02-01', 'flagged' => true],
    ];

    $challenges = [
        ['id' => 1, 'title' => 'Bike to Work', 'category' => 'transport', 'difficulty' => 'medium', 'points' => 50, 'participants' => 1243],
        ['id' => 2, 'title' => 'Zero Waste Lunch', 'category' => 'food', 'difficulty' => 'easy', 'points' => 30, 'participants' => 892],
    ];

    $activeTab = request('tab', 'overview');
    $search = request('search');
@endphp

<div class="p-4 lg:p-6 max-w-7xl mx-auto space-y-5">
    <!-- Admin header -->
    <div class="bg-gradient-to-r from-gray-900 to-gray-800 rounded-2xl p-5 text-white flex items-center justify-between animate-bounce-in">
        <div>
            <p class="text-gray-400 text-xs font-semibold uppercase tracking-wide">Admin Panel</p>
            <h1 class="text-xl font-black mt-0.5">EcoChallenge Dashboard</h1>
            <p class="text-gray-400 text-xs mt-1">Last updated: March 17, 2024 · 09:42 AM</p>
        </div>
        <div class="text-4xl">⚙️</div>
    </div>

    <!-- Tabs -->
    <div class="flex gap-1.5 bg-gray-100 p-1 rounded-xl">
        @foreach(['overview' => '📊 Overview', 'challenges' => '📋 Challenges', 'users' => '👥 Users'] as $tab => $label)
            <a
                href="{{ route('admin', ['tab' => $tab]) }}"
                class="flex-1 py-2.5 rounded-lg text-sm font-semibold text-center transition-all capitalize {{ $activeTab === $tab ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}"
            >
                {{ $label }}
            </a>
        @endforeach
    </div>

    @if($activeTab === 'overview')
        <div class="space-y-5">
            <!-- Stats cards -->
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach([
                    ['label' => 'Total Users', 'value' => number_format($adminStats['totalUsers']), 'sub' => '+' . $adminStats['newUsersThisWeek'] . ' this week', 'bg' => 'bg-blue-50 border-blue-100', 'icon' => '👥'],
                    ['label' => 'Active Today', 'value' => number_format($adminStats['activeToday']), 'sub' => round(($adminStats['activeToday'] / $adminStats['totalUsers']) * 100) . '% of all users', 'bg' => 'bg-green-50 border-green-100', 'icon' => '📈'],
                    ['label' => 'Challenges Done', 'value' => number_format($adminStats['challengesCompleted']), 'sub' => $adminStats['completionRate'] . '% completion rate', 'bg' => 'bg-emerald-50 border-emerald-100', 'icon' => '✅'],
                    ['label' => 'CO₂ Saved (total)', 'value' => round($adminStats['totalCO2Saved'] / 1000, 1) . 't', 'sub' => 'metric tonnes', 'bg' => 'bg-teal-50 border-teal-100', 'icon' => '🌿'],
                    ['label' => 'New Users (week)', 'value' => number_format($adminStats['newUsersThisWeek']), 'sub' => '+23% vs last week', 'bg' => 'bg-purple-50 border-purple-100', 'icon' => '🚀'],
                    ['label' => 'Completion Rate', 'value' => $adminStats['completionRate'] . '%', 'sub' => 'of started challenges', 'bg' => 'bg-orange-50 border-orange-100', 'icon' => '🎯'],
                ] as $i => $stat)
                    <div class="{{ $stat['bg'] }} border rounded-2xl p-4 animate-count-in" style="animation-delay: {{ $i * 0.08 }}s">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div class="p-2 bg-white rounded-lg shadow-sm text-xl">{{ $stat['icon'] }}</div>
                        </div>
                        <p class="text-xl font-black text-gray-900">{{ $stat['value'] }}</p>
                        <p class="text-xs font-semibold text-gray-700 mt-0.5">{{ $stat['label'] }}</p>
                        <p class="text-[10px] text-gray-500 mt-0.5">{{ $stat['sub'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @elseif($activeTab === 'challenges')
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500">{{ count($challenges) }} challenges</p>
                <button class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg> Add Challenge
                </button>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden animate-bounce-in">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="text-left px-4 py-3 text-xs font-bold text-gray-600">Challenge</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-gray-600">Category</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-gray-600">Difficulty</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-gray-600">Points</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($challenges as $ch)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3">
                                    <p class="text-sm font-semibold text-gray-900">{{ $ch['title'] }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-xs font-semibold capitalize bg-gray-100 text-gray-700 px-2.5 py-1 rounded-full">{{ $ch['category'] }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-green-100 text-green-700">{{ $ch['difficulty'] }}</span>
                                </td>
                                <td class="px-4 py-3 text-sm font-bold text-gray-900">{{ $ch['points'] }}</td>
                                <td class="px-4 py-3">
                                    <button class="p-1.5 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="space-y-4">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden animate-bounce-in">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="text-left px-4 py-3 text-xs font-bold text-gray-600">User</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-gray-600">Points</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-gray-600">Status</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($adminUsers as $user)
                            <tr class="hover:bg-gray-50 transition-colors {{ $user['flagged'] ? 'bg-red-50/50' : '' }}">
                                <td class="px-4 py-3">
                                    <p class="text-sm font-semibold text-gray-900">{{ $user['name'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $user['email'] }}</p>
                                </td>
                                <td class="px-4 py-3 text-sm font-bold text-gray-900">{{ number_format($user['points']) }}</td>
                                <td class="px-4 py-3">
                                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $user['flagged'] ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                        {{ $user['flagged'] ? '⚠️ Flagged' : $user['status'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <button class="p-1.5 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
