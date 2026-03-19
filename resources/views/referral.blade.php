@extends('layouts.app')

@section('title', 'Refer Friends - EcoChallenge')

@section('content')
@php
    $currentUser = [
        'referralCode' => 'MAYA2024',
        'streak' => 23,
        'carbonSaved' => 142.6,
        'rank' => 4,
    ];

    $referrals = [
        ['id' => 1, 'name' => 'Sofia Ramirez', 'joined' => '2024-03-10', 'pointsEarned' => 75, 'status' => 'active'],
        ['id' => 2, 'name' => 'Lucas Müller', 'joined' => '2024-03-05', 'pointsEarned' => 75, 'status' => 'active'],
        ['id' => 3, 'name' => 'Emma Wilson', 'joined' => '2024-02-20', 'pointsEarned' => 75, 'status' => 'active'],
        ['id' => 4, 'name' => 'Finn Olsen', 'joined' => '2024-02-10', 'pointsEarned' => 75, 'status' => 'active'],
        ['id' => 5, 'name' => 'Pending User', 'joined' => null, 'pointsEarned' => 0, 'status' => 'pending'],
    ];

    $activereferrals = collect($referrals)->where('status', 'active');
    $totalEarned = $activereferrals->sum('pointsEarned');
    $referralLink = "https://ecochallenge.app/join?ref=" . $currentUser['referralCode'];
@endphp

<div class="p-4 lg:p-6 max-w-4xl mx-auto space-y-6">
    <!-- Hero -->
    <div class="eco-gradient rounded-3xl p-6 text-white eco-pattern animate-bounce-in">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
            <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center flex-shrink-0 animate-float">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"><path d="m11 17 2 2a1 1 0 1 0 3-3"/><path d="m14 14 2.5 2.5a1 1 0 1 0 3-3l-3.88-3.88a3 3 0 0 0-4.24 0l-.88.88a1 1 0 1 1-3-3l2.81-2.81a5.79 5.79 0 0 1 7.06-.87l.47.28a2 2 0 0 0 1.42.25L21 4"/><path d="m21 3 1 11h-2"/><path d="M3 3 2 14l6.5 6.5a1 1 0 1 0 3-3"/><path d="M3 4h8"/></svg>
            </div>
            <div>
                <h1 class="text-2xl font-black">Invite Friends, Save the Planet!</h1>
                <p class="text-green-100 text-sm mt-1">
                    Earn <strong>75 points</strong> for every friend who joins using your code. They get <strong>50 bonus points</strong> too!
                </p>
                <div class="mt-3 flex items-center gap-3 flex-wrap">
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl px-3 py-1.5">
                        <span class="text-xs text-green-200">Your referrals</span>
                        <span class="ml-2 font-bold">{{ $activereferrals->count() }}</span>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl px-3 py-1.5">
                        <span class="text-xs text-green-200">Points earned</span>
                        <span class="ml-2 font-bold">{{ $totalEarned }} pts</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Referral code & link -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <!-- Code -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 animate-count-in">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-3">Your Referral Code</p>
            <div class="flex items-center gap-3 bg-green-50 border border-green-200 rounded-xl px-4 py-3">
                <span class="text-2xl font-black text-green-700 tracking-widest flex-1">{{ $currentUser['referralCode'] }}</span>
                <button class="p-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                </button>
            </div>
            <p class="text-[10px] text-gray-400 mt-2">Share this code or send the link below</p>
        </div>

        <!-- Link -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 animate-count-in" style="animation-delay: 0.1s">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-3">Referral Link</p>
            <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5">
                <span class="text-xs text-gray-600 flex-1 truncate">{{ $referralLink }}</span>
                <button class="p-1.5 bg-gray-700 hover:bg-gray-800 text-white rounded-lg transition-colors flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                </button>
            </div>
            <p class="text-[10px] text-gray-400 mt-2">Copy and share anywhere</p>
        </div>
    </div>

    <!-- Share buttons -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-sm font-bold text-gray-900 mb-4">Share Via</p>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            @foreach([
                ['label' => 'Twitter / X', 'color' => 'hover:bg-blue-50 hover:border-blue-200 hover:text-blue-700', 'svgPath' => '<path d="M4 4l11.733 16H20L8.267 4z"/><path d="M4 20l6.768-6.768M15.232 9.232 20 4"/>'],
                ['label' => 'WhatsApp', 'color' => 'hover:bg-green-50 hover:border-green-200 hover:text-green-700', 'svgPath' => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>'],
                ['label' => 'Email', 'color' => 'hover:bg-red-50 hover:border-red-200 hover:text-red-700', 'svgPath' => '<rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>'],
                ['label' => 'More...', 'color' => 'hover:bg-purple-50 hover:border-purple-200 hover:text-purple-700', 'svgPath' => '<circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" x2="15.42" y1="13.51" y2="17.49"/><line x1="15.41" x2="8.59" y1="6.51" y2="10.49"/>'],
            ] as $btn)
                <button class="flex flex-col items-center gap-2 p-4 rounded-xl border border-gray-200 text-gray-600 transition-all {{ $btn['color'] }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $btn['svgPath'] !!}</svg>
                    <span class="text-xs font-semibold">{{ $btn['label'] }}</span>
                </button>
            @endforeach
        </div>
    </div>

    <!-- How it works -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-sm font-bold text-gray-900 mb-5">How It Works</p>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            @foreach([
                ['step' => '1', 'svgPath' => '<circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" x2="15.42" y1="13.51" y2="17.49"/><line x1="15.41" x2="8.59" y1="6.51" y2="10.49"/>', 'title' => 'Share Your Code', 'desc' => 'Send your unique referral code or link to friends'],
                ['step' => '2', 'svgPath' => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>', 'title' => 'Friend Joins', 'desc' => 'Your friend signs up and enters your referral code'],
                ['step' => '3', 'svgPath' => '<path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/>', 'title' => 'Both Earn Points', 'desc' => 'You get 75 pts, they get 50 bonus pts to start!'],
            ] as $step)
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-green-600 text-white text-sm font-bold flex items-center justify-center flex-shrink-0">
                        {{ $step['step'] }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600">{!! $step['svgPath'] !!}</svg>
                            <p class="text-sm font-bold text-gray-900">{{ $step['title'] }}</p>
                        </div>
                        <p class="text-xs text-gray-500">{{ $step['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Referrals list -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-900">Your Referrals</h3>
                <span class="text-xs bg-green-100 text-green-700 font-bold px-2.5 py-1 rounded-full">{{ $activereferrals->count() }} active</span>
            </div>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($referrals as $i => $ref)
                <div class="flex items-center gap-3 px-5 py-3 animate-count-in" style="animation-delay: {{ $i * 0.05 }}s">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                        {{ substr($ref['name'], 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900">{{ $ref['name'] }}</p>
                        <p class="text-xs text-gray-500">{{ $ref['joined'] ? 'Joined ' . $ref['joined'] : 'Pending...' }}</p>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        @if($ref['status'] === 'active')
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="currentColor" class="text-yellow-500"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            <span class="text-sm font-bold text-green-700">+{{ $ref['pointsEarned'] }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-green-500"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <span class="text-xs text-gray-400">Pending</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
