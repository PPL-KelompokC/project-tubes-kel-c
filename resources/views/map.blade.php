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

    $filteredPins = $events;
@endphp

<div class="p-4 lg:p-6 max-w-6xl mx-auto space-y-5">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Header with Suggest Button -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Community Map</h1>
            <p class="text-xs text-gray-500">Discover and join local environmental events</p>
        </div>
        <button onclick="document.getElementById('suggestModal').classList.remove('hidden')" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-xl transition-all shadow-lg shadow-green-100 flex items-center gap-2">
            <span>➕</span> Add Event
        </button>
    </div>

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
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Live Map</h3>
                        <p class="text-xs text-gray-500">{{ $filteredPins->count() }} events showing</p>
                    </div>
                    <div class="flex items-center gap-1.5 bg-green-50 border border-green-100 rounded-xl px-3 py-1.5">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-xs font-semibold text-green-700">Active</span>
                    </div>
                </div>

                <div class="relative w-full h-[450px]" style="background: linear-gradient(135deg, #e0f2fe 0%, #f0fdf4 40%, #e0f7fa 100%)">
                    <!-- Street lines SVG -->
                    <svg class="absolute inset-0 w-full h-full opacity-30" xmlns="http://www.w3.org/2000/svg">
                        <line x1="10%" y1="45%" x2="90%" y2="45%" stroke="#16a34a" stroke-width="2"/>
                        <line x1="50%" y1="10%" x2="50%" y2="90%" stroke="#16a34a" stroke-width="2"/>
                        <circle cx="30%" cy="30%" r="50" fill="#16a34a" fill-opacity="0.05"/>
                        <circle cx="70%" cy="60%" r="80" fill="#16a34a" fill-opacity="0.05"/>
                    </svg>

                    @foreach($filteredPins as $pin)
                        @php $config = $eventTypeConfig[$pin->type]; @endphp
                        <div class="absolute group cursor-pointer" style="left: {{ $pin->x }}%; top: {{ $pin->y }}%; transform: translate(-50%, -100%)">
                            <div class="relative flex flex-col items-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center shadow-lg border-2 border-white text-white transition-transform group-hover:scale-110" style="background-color: {{ $config['color'] }}">
                                    <span class="text-lg">{{ $config['emoji'] }}</span>
                                </div>
                                <div class="w-0.5 h-3" style="background-color: {{ $config['color'] }}"></div>
                            </div>
                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block z-20 w-48">
                                <div class="bg-gray-900 text-white text-[11px] rounded-xl p-2.5 shadow-2xl">
                                    <p class="font-bold border-b border-gray-700 pb-1.5 mb-1.5">{{ $pin->name }}</p>
                                    <p class="text-gray-300 line-clamp-2 mb-1.5">{{ $pin->description }}</p>
                                    <div class="flex items-center justify-between text-[10px]">
                                        <span class="text-gray-400">{{ \Carbon\Carbon::parse($pin->date)->format('M d, Y') }}</span>
                                        <span class="font-bold text-green-400">{{ $pin->participants }} joining</span>
                                    </div>
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
            @forelse($filteredPins as $event)
                @php $config = $eventTypeConfig[$event->type]; @endphp
                <div class="bg-white rounded-xl border border-gray-100 p-3.5 transition-all hover:shadow-md">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl {{ $config['bg'] }} flex items-center justify-center flex-shrink-0" style="color: {{ $config['color'] }}">
                            <span class="text-lg">{{ $config['emoji'] }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start">
                                <p class="text-xs font-bold text-gray-900 truncate">{{ $event->name }}</p>
                                <span class="text-[10px] font-bold {{ $config['textColor'] }}">{{ $config['label'] }}</span>
                            </div>
                            <p class="text-[10px] text-gray-500 mt-1 line-clamp-1">{{ $event->description }}</p>
                            <div class="flex items-center gap-2 mt-2">
                                <p class="text-[10px] text-gray-400">{{ \Carbon\Carbon::parse($event->date)->format('M d') }}</p>
                                <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                <p class="text-[10px] font-semibold text-green-600">{{ $event->participants }} joining</p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl border border-dashed border-gray-200 p-8 text-center">
                    <p class="text-xs text-gray-500">No events found in this area.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Suggest Event Modal -->
<div id="suggestModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden animate-bounce-in shadow-2xl">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between bg-green-50">
            <div>
                <h3 class="font-bold text-gray-900">Suggest Community Event</h3>
                <p class="text-xs text-green-700">Add an event to the map for review</p>
            </div>
            <button onclick="document.getElementById('suggestModal').classList.add('hidden')" class="w-8 h-8 rounded-full hover:bg-white flex items-center justify-center transition-colors">
                <span class="text-gray-400">✕</span>
            </button>
        </div>

        <form action="{{ route('events.store') }}" method="POST" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5">Event Name</label>
                <input type="text" name="name" required placeholder="e.g. Mangrove Planting Day" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition-all">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Event Type</label>
                    <select name="type" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition-all appearance-none bg-no-repeat bg-[right_0.5rem_center]">
                        <option value="cleanup">🌿 Cleanup</option>
                        <option value="workshop">⚡ Workshop</option>
                        <option value="nature">🌳 Nature</option>
                        <option value="awareness">📣 Awareness</option>
                        <option value="transport">🚗 Transport</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Event Date</label>
                    <input type="date" name="date" required min="{{ date('Y-m-d') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition-all">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5">Description</label>
                <textarea name="description" required rows="3" placeholder="Tell us more about the activity..." class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition-all resize-none"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5">Map Location (Click on preview to set)</label>
                <div class="relative w-full h-32 bg-gray-50 rounded-xl border border-dashed border-gray-300 overflow-hidden cursor-crosshair" id="mapSelector">
                    <div id="selectorPin" class="absolute w-6 h-6 -ml-3 -mt-6 hidden pointer-events-none">
                        <div class="w-6 h-6 rounded-full bg-green-600 border-2 border-white shadow-lg flex items-center justify-center text-[10px] text-white">📍</div>
                    </div>
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <span class="text-[10px] text-gray-400 font-semibold">Tap to mark location</span>
                    </div>
                </div>
                <input type="hidden" name="x" id="coordX" required>
                <input type="hidden" name="y" id="coordY" required>
            </div>

            <button type="submit" class="w-full py-3 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-green-100 mt-2">
                Submit Suggestion
            </button>
        </form>
    </div>
</div>

<script>
    const mapSelector = document.getElementById('mapSelector');
    const selectorPin = document.getElementById('selectorPin');
    const coordX = document.getElementById('coordX');
    const coordY = document.getElementById('coordY');

    mapSelector.addEventListener('click', (e) => {
        const rect = mapSelector.getBoundingClientRect();
        const x = ((e.clientX - rect.left) / rect.width) * 100;
        const y = ((e.clientY - rect.top) / rect.height) * 100;

        selectorPin.style.left = x + '%';
        selectorPin.style.top = y + '%';
        selectorPin.classList.remove('hidden');

        coordX.value = x.toFixed(2);
        coordY.value = y.toFixed(2);
    });
</script>
 @endsection
