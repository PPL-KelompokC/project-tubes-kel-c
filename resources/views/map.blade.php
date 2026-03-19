@extends('layouts.app')

@section('title', 'Community Map - EcoChallenge')

@section('content')
@php
    $eventTypeConfig = [
        'cleanup' => ['color' => '#22c55e', 'bg' => 'bg-green-100', 'textColor' => 'text-green-700', 'emoji' => '🌿', 'label' => 'Cleanup'],
        'workshop' => ['color' => '#3b82f6', 'bg' => 'bg-blue-100', 'textColor' => 'text-blue-700', 'emoji' => '⚡', 'label' => 'Workshop'],
        'nature' => ['color' => '#10b981', 'bg' => 'bg-emerald-100', 'textColor' => 'text-emerald-700', 'emoji' => '🌳', 'label' => 'Nature'],
        'awareness' => ['color' => '#f97316', 'bg' => 'bg-orange-100', 'textColor' => 'text-orange-700', 'emoji' => '📣', 'label' => 'Awareness'],
        'transport' => ['color' => '#8b5cf6', 'bg' => 'bg-purple-100', 'textColor' => 'text-purple-700', 'emoji' => '🚗', 'label' => 'Transport'],
    ];

    $mapPins = [
        ['id' => 1, 'name' => 'SF Bay Cleanup', 'type' => 'cleanup', 'date' => '2024-03-20', 'participants' => 45, 'x' => 52, 'y' => 48, 'description' => 'Join us to clean up Ocean Beach!'],
        ['id' => 2, 'name' => 'Solar Workshop', 'type' => 'workshop', 'date' => '2024-03-22', 'participants' => 30, 'x' => 65, 'y' => 35, 'description' => 'Learn how to install home solar.'],
        ['id' => 3, 'name' => 'Urban Farming Day', 'type' => 'nature', 'date' => '2024-03-25', 'participants' => 60, 'x' => 38, 'y' => 50, 'description' => 'Learn to grow your own food.'],
    ];

    $activeFilter = request('filter');
    $filteredPins = collect($mapPins)->filter(fn($p) => !$activeFilter || $p['type'] === $activeFilter);
@endphp

<div class="p-4 lg:p-6 max-w-6xl mx-auto space-y-5">
    <!-- Filter pills -->
    <div class="flex gap-2 flex-wrap">
        <a href="{{ route('map') }}" class="px-4 py-2 rounded-full text-xs font-semibold transition-all border {{ !$activeFilter ? 'bg-green-600 text-white border-green-600' : 'bg-white text-gray-600 border-gray-200 hover:border-green-300' }}">🌍 All Events</a>
        @foreach($eventTypeConfig as $type => $config)
            <a href="{{ route('map', ['filter' => $type]) }}" class="flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-semibold transition-all border {{ $activeFilter === $type ? $config['bg'] . ' ' . $config['textColor'] . ' border-current' : 'bg-white text-gray-600 border-gray-200 hover:border-green-300' }}">
                {{ $config['label'] }}
            </a>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Map visualization -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden animate-bounce-in">
                <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Community Map</h3>
                        <p class="text-xs text-gray-500">{{ $filteredPins->count() }} eco events nearby</p>
                    </div>
                    <div class="flex items-center gap-1.5 bg-green-50 border border-green-100 rounded-xl px-3 py-1.5">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-xs font-semibold text-green-700">Live Map</span>
                    </div>
                </div>

                <div class="relative w-full h-[400px]" style="background: linear-gradient(135deg, #e0f2fe 0%, #f0fdf4 40%, #e0f7fa 100%)">
                    <!-- Street lines SVG -->
                    <svg class="absolute inset-0 w-full h-full opacity-30" xmlns="http://www.w3.org/2000/svg">
                        <line x1="10%" y1="45%" x2="90%" y2="45%" stroke="#16a34a" stroke-width="2"/>
                        <line x1="50%" y1="10%" x2="50%" y2="90%" stroke="#16a34a" stroke-width="2"/>
                    </svg>

                    @foreach($filteredPins as $pin)
                        @php $config = $eventTypeConfig[$pin['type']]; @endphp
                        <div class="absolute group cursor-pointer" style="left: {{ $pin['x'] }}%; top: {{ $pin['y'] }}%; transform: translate(-50%, -100%)">
                            <div class="relative flex flex-col items-center">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center shadow-lg border-2 border-white text-white" style="background-color: {{ $config['color'] }}">
                                    <span>{{ $config['emoji'] }}</span>
                                </div>
                                <div class="w-0.5 h-3" style="background-color: {{ $config['color'] }}"></div>
                            </div>
                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block z-20 w-32">
                                <div class="bg-gray-900 text-white text-[10px] rounded-lg px-2 py-1.5 text-center shadow-xl">
                                    <p class="font-semibold truncate">{{ $pin['name'] }}</p>
                                    <p class="text-gray-300">{{ $pin['participants'] }} joining</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Event list -->
        <div class="space-y-3">
            <h3 class="text-sm font-bold text-gray-900">Upcoming Events</h3>
            @foreach($filteredPins as $event)
                @php $config = $eventTypeConfig[$event['type']]; @endphp
                <div class="bg-white rounded-xl border border-gray-100 p-3.5 transition-all hover:shadow-md animate-count-in">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl {{ $config['bg'] }} flex items-center justify-center flex-shrink-0" style="color: {{ $config['color'] }}">
                            <span>{{ $config['emoji'] }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-gray-900 truncate">{{ $event['name'] }}</p>
                            <p class="text-[10px] font-semibold {{ $config['textColor'] }}">{{ $config['label'] }}</p>
                            <p class="text-[10px] text-gray-500 mt-1">{{ $event['date'] }} · {{ $event['participants'] }} joining</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
