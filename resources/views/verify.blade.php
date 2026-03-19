@extends('layouts.app')

@section('title', 'Verification - EcoChallenge')

@section('content')
@php
    $pendingReview = [
        ['id' => 1, 'title' => 'Bike to Work', 'category' => 'transport', 'points' => 50, 'co2Saved' => 2.3],
        ['id' => 3, 'title' => 'Recycling Sort', 'category' => 'waste', 'points' => 20, 'co2Saved' => 0.8],
    ];

    $verifiedCompletions = [
        ['id' => 2, 'title' => 'Zero Waste Lunch', 'category' => 'food', 'points' => 30, 'co2Saved' => 1.1, 'imageUrl' => 'https://images.unsplash.com/photo-1770914755925-6468b9050176?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&w=400'],
    ];

    $selectedChallengeId = request('challenge_id');
@endphp

<div class="p-4 lg:p-6 max-w-5xl mx-auto space-y-6">
    <!-- Back link -->
    <a href="{{ route('challenges') }}" class="flex items-center gap-2 text-sm text-gray-500 hover:text-green-600 font-medium transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" x2="5" y1="12" y2="12"/><polyline points="12 19 5 12 12 5"/></svg> Back to Challenges
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Upload area -->
        <div class="space-y-4">
            <div>
                <h2 class="text-base font-bold text-gray-900">📸 Submit Proof</h2>
                <p class="text-xs text-gray-500 mt-0.5">Upload a photo to verify your challenge completion</p>
            </div>

            <!-- Challenge selector -->
            <div>
                <label class="text-xs font-bold text-gray-600 block mb-2">Select Challenge</label>
                <div class="space-y-2 max-h-48 overflow-y-auto">
                    @foreach($pendingReview as $ch)
                        <button
                            class="w-full text-left flex items-center gap-3 p-3 rounded-xl border transition-all {{ $selectedChallengeId == $ch['id'] ? 'border-green-400 bg-green-50 ring-2 ring-green-100' : 'border-gray-200 bg-white hover:border-green-300' }}"
                        >
                            <div class="text-lg flex-shrink-0">
                                @if($ch['category'] === 'transport') 🚴 @elseif($ch['category'] === 'food') 🥗 @else ♻️ @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $ch['title'] }}</p>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-xs text-gray-500">+{{ $ch['points'] }} pts</span>
                                    <span class="text-[10px] text-gray-400">·</span>
                                    <span class="text-xs text-green-600">{{ $ch['co2Saved'] }}kg CO₂</span>
                                </div>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Drop zone -->
            <div class="relative border-2 border-dashed border-gray-200 bg-gray-50 rounded-2xl p-8 text-center cursor-pointer hover:border-green-300 hover:bg-green-50 transition-all">
                <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                </div>
                <p class="text-sm font-bold text-gray-700">Drop your photo here</p>
                <p class="text-xs text-gray-500 mt-1">or click to browse • JPG, PNG up to 10MB</p>
            </div>

            <button class="w-full py-3 bg-green-600 hover:bg-green-700 disabled:opacity-50 text-white font-bold rounded-2xl transition-all flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                Submit for Verification
            </button>
        </div>

        <!-- Status panel -->
        <div class="space-y-4">
            <div>
                <h2 class="text-base font-bold text-gray-900">📋 Verification Status</h2>
                <p class="text-xs text-gray-500 mt-0.5">Track your submitted proofs</p>
            </div>

            <!-- Verified -->
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">✅ Verified</p>
                <div class="space-y-2">
                    @foreach($verifiedCompletions as $ch)
                        <div class="bg-white rounded-xl border border-green-100 p-3 flex items-center gap-3 animate-count-in">
                            <img src="{{ $ch['imageUrl'] }}" alt="" class="w-12 h-12 rounded-lg object-cover flex-shrink-0" />
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $ch['title'] }}</p>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <div class="flex items-center gap-1 text-xs font-semibold text-green-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg> Verified
                                    </div>
                                    <div class="flex items-center gap-1 text-xs text-green-700">
                                        <span class="text-yellow-500">⭐</span> +{{ $ch['points'] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Tips -->
            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 animate-count-in">
                <p class="text-xs font-bold text-blue-800 mb-2">📸 Photo Tips for Fast Approval</p>
                <ul class="space-y-1">
                    @foreach(['activity visible', 'include yourself', 'good lighting', 'contextual'] as $tip)
                        <li class="flex items-start gap-2 text-xs text-blue-700">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-blue-400 mt-0.5"><path d="M20 6 9 17l-5-5"/></svg>
                            {{ ucfirst($tip) }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
