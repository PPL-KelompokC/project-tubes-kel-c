@extends('layouts.app')

@section('title', 'Notifications - EcoChallenge')

@section('content')
@php
    $notifications = [
        ['id' => 1, 'type' => 'streak', 'title' => 'Streak Alert!', 'message' => 'You\'re on a 23-day streak! Don\'t break it today.', 'time' => '2 min ago', 'read' => false],
        ['id' => 2, 'type' => 'challenge', 'title' => 'New Challenges Available', 'message' => '3 new daily challenges are waiting for you!', 'time' => '1 hour ago', 'read' => false],
        ['id' => 3, 'type' => 'social', 'title' => 'Alex Chen liked your post', 'message' => 'Alex liked your recycling challenge completion.', 'time' => '2 hours ago', 'read' => false],
        ['id' => 4, 'type' => 'badge', 'title' => 'Badge Nearly Unlocked!', 'message' => 'Complete 3 more challenges to unlock Carbon Crusher!', 'time' => '5 hours ago', 'read' => true],
        ['id' => 5, 'type' => 'leaderboard', 'title' => 'You moved up!', 'message' => 'You\'re now ranked #4 on the weekly leaderboard!', 'time' => '1 day ago', 'read' => true],
    ];

    $notifConfig = [
        'streak' => ['svgPath' => '<path d="M12 2c0 0-5.5 5-5.5 10.5A5.5 5.5 0 0 0 12 18a5.5 5.5 0 0 0 5.5-5.5C17.5 7 12 2 12 2Z"/>', 'bg' => 'bg-orange-100', 'label' => 'Streak'],
        'challenge' => ['svgPath' => '<rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/>', 'bg' => 'bg-green-100', 'label' => 'Challenge'],
        'badge' => ['svgPath' => '<path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/>', 'bg' => 'bg-yellow-100', 'label' => 'Badge'],
        'leaderboard' => ['svgPath' => '<polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/>', 'bg' => 'bg-blue-100', 'label' => 'Ranking'],
        'social' => ['svgPath' => '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>', 'bg' => 'bg-pink-100', 'label' => 'Social'],
    ];

    $filter = request('filter', 'all');
    $filtered = collect($notifications)->filter(fn($n) => $filter === 'all' || !$n['read']);
    $unreadCount = collect($notifications)->where('read', false)->count();
@endphp

<div class="p-4 lg:p-6 max-w-3xl mx-auto space-y-5">
    <!-- Header controls -->
    <div class="flex items-center justify-between gap-3 flex-wrap">
        <div class="flex gap-2">
            @foreach(['all' => 'All', 'unread' => 'Unread'] as $f => $label)
                <a
                    href="{{ route('notifications', ['filter' => $f]) }}"
                    class="px-4 py-2 rounded-xl text-sm font-semibold transition-all border {{ $filter === $f ? 'bg-green-600 text-white border-green-600' : 'bg-white text-gray-600 border-gray-200 hover:border-green-300' }}"
                >
                    {{ $label }}
                    @if($f === 'unread' && $unreadCount > 0)
                        <span class="ml-1.5 bg-red-500 text-white text-[10px] px-1.5 py-0.5 rounded-full">{{ $unreadCount }}</span>
                    @endif
                </a>
            @endforeach
        </div>
        @if($unreadCount > 0)
            <button class="text-sm text-green-600 hover:text-green-700 font-semibold flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17l-5-5"/></svg>
                Mark all read
            </button>
        @endif
    </div>

    <!-- Notifications list -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden animate-bounce-in">
        @if($filtered->count() === 0)
            <div class="py-16 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-200 mx-auto mb-3"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                <p class="text-sm font-semibold text-gray-600">All caught up!</p>
                <p class="text-xs text-gray-400 mt-1">No {{ $filter === 'unread' ? 'unread ' : '' }}notifications</p>
            </div>
        @else
            <div class="divide-y divide-gray-50">
                @foreach($filtered as $i => $notif)
                    @php $config = $notifConfig[$notif['type']] ?? $notifConfig['challenge']; @endphp
                    <div class="flex items-start gap-3 px-4 py-4 hover:bg-gray-50 transition-colors group {{ !$notif['read'] ? 'bg-green-50/40' : '' }} animate-count-in" style="animation-delay: {{ $i * 0.04 }}s">
                        <div class="w-10 h-10 rounded-xl {{ $config['bg'] }} flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $config['svgPath'] !!}</svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <p class="text-sm font-semibold {{ !$notif['read'] ? 'text-gray-900' : 'text-gray-700' }}">{{ $notif['title'] }}</p>
                                <span class="text-[10px] text-gray-400 flex-shrink-0 mt-0.5">{{ $notif['time'] }}</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">{{ $notif['message'] }}</p>
                            <div class="flex items-center gap-2 mt-1.5">
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $config['bg'] }} text-gray-700">
                                    {{ $config['label'] }}
                                </span>
                                @if(!$notif['read'])
                                    <button class="text-[10px] text-green-600 hover:text-green-700 font-medium">Mark read</button>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-1.5 flex-shrink-0">
                            @if(!$notif['read']) <div class="w-2.5 h-2.5 bg-green-500 rounded-full"></div> @endif
                            <button class="p-1 text-gray-300 hover:text-red-400 opacity-0 group-hover:opacity-100 transition-all rounded-lg hover:bg-red-50">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Notification preferences -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <h3 class="text-sm font-bold text-gray-900 mb-4">Notification Preferences</h3>
        <div class="space-y-3">
            @foreach(['streak' => 'Streak Alerts', 'challenge' => 'Challenge Updates', 'badge' => 'Badge Achievements', 'leaderboard' => 'Ranking Changes', 'social' => 'Social Interaction'] as $key => $label)
                @php $config = $notifConfig[$key] ?? ['bg' => 'bg-gray-100', 'emoji' => '🔔']; @endphp
                <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg {{ $config['bg'] }} flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $config['svgPath'] !!}</svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ $label }}</p>
                            <p class="text-xs text-gray-500">Stay updated on your {{ strtolower($key) }} activities.</p>
                        </div>
                    </div>
                    <button class="relative w-11 h-6 rounded-full bg-green-500 transition-all">
                        <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow-sm translate-x-5 transition-transform"></div>
                    </button>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
