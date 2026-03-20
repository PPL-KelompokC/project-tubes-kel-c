@extends('layouts.app')

@section('title', 'Rewards - EcoChallenge')

@section('content')
@php
    $user = Auth::user();
    $points = $user->points;
    $activeTab = request('tab', 'redeem');

    $rewardItems = [
        ['id' => 1, 'name' => 'Plant a Real Tree', 'description' => 'We partner with Eden Reforestation to plant a tree in your name in Madagascar.', 'points' => 200, 'category' => 'nature', 'svgPath' => '<path d="M17 14c.83-1.071 1.5-2.547 1.5-4.5C18.5 5.686 15.314 3 12 3S5.5 5.686 5.5 9.5c0 1.953.67 3.429 1.5 4.5"/><path d="M12 3v11"/><path d="M9 21h6"/><path d="M12 16v5"/>', 'svgColor' => 'text-green-600 bg-green-50', 'available' => true],
        ['id' => 2, 'name' => 'Carbon Offset (1 tonne)', 'description' => 'Offset 1 tonne of CO₂ through certified carbon credits.', 'points' => 500, 'category' => 'carbon', 'svgPath' => '<path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>', 'svgColor' => 'text-emerald-600 bg-emerald-50', 'available' => true],
        ['id' => 3, 'name' => 'Eco-friendly Tote Bag', 'description' => 'A premium organic cotton tote bag shipped to your door.', 'points' => 350, 'category' => 'product', 'svgPath' => '<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" x2="21" y1="6" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>', 'svgColor' => 'text-purple-600 bg-purple-50', 'available' => true],
        ['id' => 4, 'name' => 'Solar Phone Charger', 'description' => 'Portable solar-powered phone charger for eco travel.', 'points' => 800, 'category' => 'product', 'svgPath' => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/>', 'svgColor' => 'text-yellow-600 bg-yellow-50', 'available' => false],
        ['id' => 5, 'name' => 'Donate to WWF', 'description' => 'Donate 5 USD equivalent to World Wildlife Fund.', 'points' => 250, 'category' => 'charity', 'svgPath' => '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>', 'svgColor' => 'text-red-500 bg-red-50', 'available' => true],
        ['id' => 6, 'name' => 'Bamboo Toothbrush Set', 'description' => 'Set of 4 biodegradable bamboo toothbrushes.', 'points' => 150, 'category' => 'product', 'svgPath' => '<path d="M12 2v20"/><path d="M7 5c0-1.7 1.3-3 3-3h4c1.7 0 3 1.3 3 3"/><path d="M7 5c0 1.7 1.3 3 3 3h4c1.7 0 3-1.3 3-3"/>', 'svgColor' => 'text-teal-600 bg-teal-50', 'available' => true],
    ];

    $transactions = $user->submissions()->where('status', 'verified')->latest()->take(10)->get()->map(function($s) {
        return [
            'id' => $s->id,
            'type' => 'earn',
            'description' => 'Challenge: ' . $s->challenge->title,
            'points' => $s->points_awarded,
            'date' => $s->verified_at ? $s->verified_at->format('Y-m-d') : $s->updated_at->format('Y-m-d'),
            'category' => 'activity'
        ];
    });
@endphp

<div class="p-4 lg:p-6 max-w-5xl mx-auto space-y-5">
    <!-- Points balance card -->
    <div class="rounded-3xl p-6 text-white relative overflow-hidden animate-bounce-in shadow-lg" style="background: linear-gradient(135deg, #15803d 0%, #047857 45%, #0369a1 100%);">
        <!-- Decorative overlay circles -->
        <div class="absolute inset-0 rounded-3xl" style="background-image: radial-gradient(circle at 15% 75%, rgba(52,211,153,0.18) 0%, transparent 55%), radial-gradient(circle at 85% 15%, rgba(56,189,248,0.15) 0%, transparent 55%);"></div>
        <div class="relative z-10 flex items-center justify-between gap-4">
            <div>
                <p class="text-green-200 text-sm font-medium">Your Balance</p>
                <div class="flex items-end gap-2 mt-1">
                    <span class="text-5xl font-black">{{ number_format($points) }}</span>
                    <span class="text-lg font-semibold text-green-200 mb-1">pts</span>
                </div>
                <p class="text-green-100 text-xs mt-1">Earned through eco actions</p>
            </div>
            <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center animate-float">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"><polyline points="20 12 20 22 4 22 4 12"/><rect width="22" height="5" x="1" y="7"/><line x1="12" x2="12" y1="22" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
            </div>
        </div>

        <!-- Quick stats -->
        <div class="mt-4 grid grid-cols-3 gap-3">
            @foreach([
                ['label' => 'Earned (total)', 'value' => '9,550', 'icon' => '⬆️'],
                ['label' => 'Redeemed', 'value' => '700', 'icon' => '⬇️'],
                ['label' => 'This month', 'value' => '+1,120', 'icon' => '📈'],
            ] as $s)
                <div class="bg-white/15 backdrop-blur-sm rounded-xl p-2.5 text-center">
                    <p class="text-white font-bold text-sm">{{ $s['value'] }}</p>
                    <p class="text-green-200 text-[10px]">{{ $s['label'] }}</p>
                </div>
            @endforeach
        </div>
        <div class="absolute -right-6 -bottom-6 w-32 h-32 rounded-full bg-white/5"></div>
    </div>

    <!-- Tabs -->
    <div class="flex gap-1 bg-gray-100 p-1 rounded-xl">
        @foreach(['redeem' => 'Redeem Points', 'history' => 'Transaction History'] as $t => $label)
            <a href="{{ route('rewards', ['tab' => $t]) }}" class="flex-1 py-2.5 rounded-lg text-sm font-semibold text-center transition-all {{ $activeTab === $t ? 'bg-white text-green-700 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    @if($activeTab === 'redeem')
        <div>
            <p class="text-sm text-gray-500 mb-4">Exchange your points for real-world eco impact</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($rewardItems as $i => $item)
                    @php $canAfford = $points >= $item['points']; @endphp
                    <div class="bg-white rounded-2xl border shadow-sm p-4 transition-all card-hover animate-count-in {{ $item['available'] ? 'border-gray-100' : 'border-gray-100 opacity-60' }}" style="animation-delay: {{ $i * 0.08 }}s">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-3 mx-auto {{ $item['svgColor'] }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $item['svgPath'] !!}</svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-900 text-center mb-1">{{ $item['name'] }}</h3>
                        <p class="text-xs text-gray-500 text-center line-clamp-2 mb-3">{{ $item['description'] }}</p>

                        <div class="flex items-center justify-center gap-1.5 mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="currentColor" class="text-yellow-500"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            <span class="text-base font-black text-gray-800">{{ number_format($item['points']) }}</span>
                            <span class="text-sm text-gray-500">points</span>
                        </div>

                        @if(!$item['available'])
                            <div class="text-center py-2 text-xs text-gray-400 font-medium">Coming Soon</div>
                        @else
                            <button
                                class="w-full py-2.5 rounded-xl text-sm font-bold transition-all {{ $canAfford ? 'bg-green-600 hover:bg-green-700 text-white active:scale-95' : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}"
                            >
                                {{ $canAfford ? 'Redeem Now' : 'Need ' . number_format($item['points'] - $points) . ' more pts' }}
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden animate-count-in">
            <div class="divide-y divide-gray-50">
                @foreach($transactions as $i => $tx)
                    <div class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors animate-count-in" style="animation-delay: {{ $i * 0.04 }}s">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 {{ $tx['type'] === 'earn' ? 'bg-green-100' : 'bg-orange-100' }}">
                            @if($tx['type'] === 'earn')
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600"><line x1="7" x2="17" y1="17" y2="7"/><polyline points="7 7 17 7 17 17"/></svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-orange-600"><line x1="17" x2="7" y1="7" y2="17"/><polyline points="17 17 7 17 7 7"/></svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ $tx['description'] }}</p>
                            <p class="text-xs text-gray-400">{{ $tx['date'] }}</p>
                        </div>
                        <span class="text-sm font-bold flex-shrink-0 {{ $tx['type'] === 'earn' ? 'text-green-600' : 'text-orange-600' }}">
                            {{ $tx['type'] === 'earn' ? '+' : '' }}{{ $tx['points'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
