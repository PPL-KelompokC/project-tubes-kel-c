@extends('layouts.app')

@section('title', 'Community Map - TerraVerde')

@section('content')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

@php
    $eventTypeConfig = [
        'cleanup' => ['color' => '#22c55e', 'bg' => 'bg-green-100', 'textColor' => 'text-green-700', 'emoji' => '🌿', 'label' => 'Cleanup'],
        'workshop' => ['color' => '#3b82f6', 'bg' => 'bg-blue-100', 'textColor' => 'text-blue-700', 'emoji' => '⚡', 'label' => 'Workshop'],
        'nature' => ['color' => '#10b981', 'bg' => 'bg-emerald-100', 'textColor' => 'text-emerald-700', 'emoji' => '🌳', 'label' => 'Nature'],
        'awareness' => ['color' => '#f97316', 'bg' => 'bg-orange-100', 'textColor' => 'text-orange-700', 'emoji' => '📣', 'label' => 'Awareness'],
        'transport' => ['color' => '#8b5cf6', 'bg' => 'bg-purple-100', 'textColor' => 'text-purple-700', 'emoji' => '🚗', 'label' => 'Transport'],
    ];

    $filteredPins = $events;
    $userId = Auth::id();
@endphp

<div class="p-4 lg:p-6 max-w-7xl mx-auto space-y-6">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Header and Filter Pills -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('map') }}" class="px-5 py-2.5 rounded-full text-xs font-bold transition-all border flex items-center gap-2 {{ !$activeFilter ? 'bg-green-600 text-white border-green-600 shadow-md shadow-green-200' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-300 hover:bg-gray-50' }}">
                @if(!$activeFilter) <span>🌍</span> @endif All Events
            </a>
            @foreach($eventTypeConfig as $type => $config)
                <a href="{{ route('map', ['filter' => $type]) }}" class="px-5 py-2.5 rounded-full text-xs font-bold transition-all border {{ $activeFilter === $type ? 'bg-white text-gray-900 border-gray-400 shadow-sm' : 'bg-white text-gray-500 border-gray-200 hover:border-gray-300 hover:bg-gray-50' }}">
                    {{ $config['label'] }}
                </a>
            @endforeach
        </div>
        
        <!-- Action Button -->
        <button onclick="openSuggestModal()" class="px-5 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-xs font-bold rounded-full transition-all shadow-sm flex items-center gap-2 self-start md:self-auto">
            <span>➕</span> Suggest Event
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Map Container -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col h-[600px] relative">
                
                <!-- Map Header (Overlay) -->
                <div class="absolute top-0 left-0 right-0 flex items-center justify-between px-6 py-4 z-[400] pointer-events-none">
                    <div class="bg-white/90 backdrop-blur-md px-4 py-2 rounded-2xl shadow-sm border border-gray-100 pointer-events-auto min-w-[200px] transition-all duration-300" id="map-title-container">
                        <div id="map-title-view" class="cursor-pointer group flex items-center justify-between gap-2" onclick="toggleSearch(true)" title="Click to search location">
                            <div>
                                <h3 class="text-base font-bold text-gray-900 group-hover:text-green-600 transition-colors flex items-center gap-1">
                                    <span id="map-location-title">Jakarta, Indonesia</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400 group-hover:text-green-500"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                </h3>
                                <p class="text-xs text-gray-500" id="map-visible-events">{{ $filteredPins->count() }} eco events nearby</p>
                            </div>
                        </div>
                        <div id="map-search-view" class="hidden flex items-center gap-2">
                            <input type="text" id="map-search-input" placeholder="Search city or country..." class="text-sm bg-transparent border-none focus:ring-0 p-0 w-full font-bold text-gray-900 placeholder-gray-400 outline-none">
                            <button onclick="toggleSearch(false)" class="text-gray-400 hover:text-gray-600">✕</button>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 bg-green-50/90 backdrop-blur-md border border-green-200 rounded-full px-3.5 py-1.5 shadow-sm pointer-events-auto cursor-pointer hover:bg-green-100 transition-colors" onclick="locateUser()">
                        <div class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse" id="live-indicator"></div>
                        <span class="text-[10px] font-bold text-green-700 uppercase tracking-wider" id="live-text">Locate Me</span>
                    </div>
                </div>

                <!-- Real Leaflet Map Area -->
                <div id="main-map" class="w-full h-full z-10"></div>

                <!-- Legend (Overlay) -->
                <div class="absolute bottom-6 left-6 bg-white/95 backdrop-blur-md p-4 rounded-2xl shadow-lg border border-gray-100 z-[400] min-w-[130px] pointer-events-auto">
                    <h4 class="text-[9px] font-black text-gray-500 mb-3 tracking-widest uppercase">Legend</h4>
                    <div class="space-y-3">
                        @foreach($eventTypeConfig as $typeKey => $config)
                            <div class="flex items-center gap-2.5">
                                <div class="w-2.5 h-2.5 rounded-full shadow-sm" style="background-color: {{ $config['color'] }}"></div>
                                <span class="text-xs font-semibold text-gray-600">{{ $config['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Event list -->
        <div class="space-y-4">
            <h3 class="text-base font-bold text-gray-900">Upcoming Events</h3>
            <div class="flex flex-col gap-3 h-[550px] overflow-y-auto pr-2 custom-scrollbar">
                @forelse($filteredPins as $event)
                    @php 
                        $config = $eventTypeConfig[$event->type]; 
                        $hasJoined = $event->attendingUsers->contains($userId);
                    @endphp
                    <div class="bg-white rounded-2xl border border-gray-100 p-4 transition-all hover:shadow-md hover:border-green-100 group cursor-pointer" data-card-id="{{ $event->id }}" onclick="focusEventOnMap({{ $event->id }}, {{ $event->x }}, {{ $event->y }})">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-[14px] {{ $config['bg'] }} flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform duration-300" style="color: {{ $config['color'] }}">
                                <span class="text-xl">{{ $config['emoji'] }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-900 truncate mb-1">{{ $event->name }}</p>
                                <div class="flex items-center gap-1.5 text-[11px] font-bold">
                                    <span style="color: {{ $config['color'] }}">{{ $config['label'] }}</span>
                                    <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                    <span class="text-gray-400 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                        {{ \Carbon\Carbon::parse($event->date)->format('Y-m-d') }}
                                    </span>
                                </div>
                                <div class="mt-2 text-[11px] text-gray-500 flex items-center justify-between font-medium">
                                    <div class="flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                        <span class="event-participants-count">{{ $event->participants }} participants</span>
                                    </div>
                                    <button onclick="event.stopPropagation(); toggleJoinEvent({{ $event->id }})" class="join-btn-{{ $event->id }} px-3 py-1 rounded-md text-[10px] font-bold transition-all border {{ $hasJoined ? 'bg-gray-100 text-gray-600 border-gray-200 hover:bg-red-50 hover:text-red-600 hover:border-red-200' : 'bg-green-50 text-green-700 border-green-200 hover:bg-green-600 hover:text-white hover:border-green-600' }}">
                                        {{ $hasJoined ? 'Joined' : 'Join' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl border border-dashed border-gray-200 p-8 text-center">
                        <p class="text-sm text-gray-500 font-medium">No events found in this area.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Suggest Event Modal -->
<div id="suggestModal" class="hidden fixed inset-0 z-[500] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl transition-all flex flex-col max-h-[90vh]">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between bg-green-50 flex-shrink-0">
            <div>
                <h3 class="font-bold text-gray-900">Suggest Community Event</h3>
                <p class="text-xs text-green-700">Add an event to the map for review</p>
            </div>
            <button onclick="closeSuggestModal()" class="w-8 h-8 rounded-full hover:bg-white flex items-center justify-center transition-colors">
                <span class="text-gray-400">✕</span>
            </button>
        </div>

        <form action="{{ route('events.store') }}" method="POST" class="p-5 space-y-4 overflow-y-auto custom-scrollbar">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5">Event Name</label>
                <input type="text" name="name" required placeholder="e.g. Mangrove Planting Day" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition-all">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Event Type</label>
                    <select name="type" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition-all appearance-none bg-white">
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
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-bold text-gray-700">Map Location <span class="font-normal text-gray-400">(Click on map to drop pin)</span></label>
                    <button type="button" onclick="locateSuggestUser()" class="text-[10px] font-bold text-green-600 hover:text-green-700 flex items-center gap-1">
                        📍 Use My Location
                    </button>
                </div>
                <div class="relative w-full h-48 bg-gray-50 rounded-xl border border-gray-200 overflow-hidden" id="suggest-map-container">
                    <div id="suggest-map" class="w-full h-full z-10 cursor-crosshair"></div>
                </div>
                <div class="flex gap-2 mt-2">
                    <input type="text" name="x" id="coordX" required readonly class="w-1/2 px-3 py-1.5 rounded-lg border border-gray-200 text-xs text-gray-500 bg-gray-50" placeholder="Latitude">
                    <input type="text" name="y" id="coordY" required readonly class="w-1/2 px-3 py-1.5 rounded-lg border border-gray-200 text-xs text-gray-500 bg-gray-50" placeholder="Longitude">
                </div>
            </div>

            <button type="submit" class="w-full py-3 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-green-100 mt-4">
                Submit Suggestion
            </button>
        </form>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #d1d5db; }
    
    /* Leaflet popup overrides */
    .leaflet-popup-content-wrapper { border-radius: 12px; padding: 0; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }
    .leaflet-popup-content { margin: 0; line-height: 1.5; }
    .leaflet-container a.leaflet-popup-close-button { color: #9ca3af; padding: 4px 8px; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; }
    .leaflet-container a.leaflet-popup-close-button:hover { color: #374151; background: transparent; }
</style>

<script>
    // --- Data ---
    const events = @json($filteredPins);
    const eventTypeConfig = @json($eventTypeConfig);
    const userId = {{ $userId ?? 'null' }};

    // --- Main Map Initialization ---
    // Default coordinates: Jakarta (-6.200000, 106.816666)
    const defaultLocation = [-6.200000, 106.816666];
    
    // Use CartoDB Positron for a clean, sleek, light theme map
    const tileLayerUrl = 'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png';
    const tileLayerAttr = '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>';

    const mainMap = L.map('main-map', {
        zoomControl: false // Move zoom control to bottom right
    }).setView(defaultLocation, 12);

    L.control.zoom({ position: 'bottomright' }).addTo(mainMap);

    L.tileLayer(tileLayerUrl, {
        attribution: tileLayerAttr,
        subdomains: 'abcd',
        maxZoom: 20
    }).addTo(mainMap);

    const markers = {};
    const markerGroup = L.featureGroup().addTo(mainMap);

    // --- Render Event Markers ---
    events.forEach(event => {
        const config = eventTypeConfig[event.type];
        const hasJoined = event.attending_users && event.attending_users.some(u => u.id === userId);
        const lat = parseFloat(event.x); // DB x = Latitude
        const lng = parseFloat(event.y); // DB y = Longitude

        // Custom HTML Marker using L.divIcon
        const customIcon = L.divIcon({
            className: 'custom-leaflet-marker',
            iconSize: [40, 56],
            iconAnchor: [20, 56],
            popupAnchor: [0, -60],
            html: `
                <div class="relative flex flex-col items-center group">
                    <div class="absolute inset-0 opacity-20 rounded-full blur-md scale-[2] group-hover:scale-[2.5] transition-transform duration-500 pointer-events-none" style="background-color: ${config.color}"></div>
                    <div class="w-10 h-10 rounded-full flex items-center justify-center shadow-lg border-[2.5px] border-white text-white z-10 transition-transform duration-300 group-hover:scale-110" style="background-color: ${config.color}">
                        <span class="text-base leading-none drop-shadow-sm">${config.emoji}</span>
                    </div>
                    <div class="w-[2.5px] h-4 rounded-b-full -mt-[2px] shadow-sm" style="background-color: ${config.color}"></div>
                </div>
            `
        });

        const dateStr = new Date(event.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        
        // Tooltip (Popup) Content
        const popupContent = `
            <div class="bg-white text-gray-900 p-4 w-56">
                <p class="text-sm font-bold border-b border-gray-100 pb-2 mb-2 truncate" title="${event.name}">${event.name}</p>
                <p class="text-xs text-gray-500 line-clamp-3 mb-3">${event.description || ''}</p>
                <div class="flex items-center justify-between text-[11px] font-semibold mb-3">
                    <span class="text-gray-400">${dateStr}</span>
                    <span class="event-participants-count" style="color: ${config.color}">${event.participants} joining</span>
                </div>
                <button onclick="toggleJoinEvent(${event.id})" class="join-btn-${event.id} w-full py-2 rounded-lg text-[11px] font-bold transition-all border ${hasJoined ? 'bg-gray-100 text-gray-600 border-gray-200 hover:bg-red-50 hover:text-red-600 hover:border-red-200' : 'bg-green-600 text-white border-green-600 hover:bg-green-700'}">
                    ${hasJoined ? 'Joined (Click to Leave)' : 'Join Event'}
                </button>
            </div>
        `;

        const marker = L.marker([lat, lng], { icon: customIcon })
            .bindPopup(popupContent)
            .addTo(markerGroup);

        markers[event.id] = marker;
    });

    // Fit map bounds to show all markers if there are any
    if (events.length > 0) {
        mainMap.fitBounds(markerGroup.getBounds(), { padding: [50, 50], maxZoom: 14 });
    }

    // --- Interactive Functions ---
    function focusEventOnMap(eventId, lat, lng) {
        if(lat && lng) {
            mainMap.flyTo([parseFloat(lat), parseFloat(lng)], 16, { duration: 1 });
            if(markers[eventId]) {
                setTimeout(() => markers[eventId].openPopup(), 1000);
            }
        }
    }

    function locateUser() {
        const ind = document.getElementById('live-indicator');
        const txt = document.getElementById('live-text');
        txt.innerText = "Locating...";
        ind.classList.remove('bg-green-500'); ind.classList.add('bg-orange-500');

        mainMap.locate({setView: true, maxZoom: 15});

        mainMap.once('locationfound', function(e) {
            txt.innerText = "Current Location";
            ind.classList.remove('bg-orange-500'); ind.classList.add('bg-green-500');
            document.getElementById('map-location-title').innerText = "Your Area";
            
            // Optional: add a tiny blue dot for user
            L.circleMarker(e.latlng, {
                radius: 6, fillColor: "#3b82f6", color: "#ffffff", weight: 2, opacity: 1, fillOpacity: 1
            }).addTo(mainMap);
        });

        mainMap.once('locationerror', function(e) {
            alert("Could not find your location. Please check browser permissions.");
            txt.innerText = "Locate Me";
            ind.classList.remove('bg-orange-500'); ind.classList.add('bg-green-500');
        });
    }

    // --- Suggest Event Modal Map ---
    let suggestMap = null;
    let suggestMarker = null;
    
    function openSuggestModal() {
        document.getElementById('suggestModal').classList.remove('hidden');
        
        if (!suggestMap) {
            // Initialize Suggest Map
            suggestMap = L.map('suggest-map', {
                zoomControl: true,
                attributionControl: false
            }).setView(mainMap.getCenter(), mainMap.getZoom());

            L.tileLayer(tileLayerUrl, { subdomains: 'abcd', maxZoom: 20 }).addTo(suggestMap);
            
            // Custom Draggable Marker
            const suggestIcon = L.divIcon({
                className: 'custom-leaflet-marker',
                iconSize: [32, 44],
                iconAnchor: [16, 44],
                html: `
                    <div class="relative flex flex-col items-center group cursor-grab active:cursor-grabbing">
                        <div class="w-8 h-8 rounded-full bg-green-600 border-2 border-white shadow-lg flex items-center justify-center text-white text-xs">📍</div>
                        <div class="w-1 h-3 bg-green-600 rounded-b-full shadow-sm -mt-[1px]"></div>
                    </div>
                `
            });

            suggestMap.on('click', function(e) {
                setSuggestMarker(e.latlng);
            });
            
            // Default center pin
            setSuggestMarker(mainMap.getCenter());

            function setSuggestMarker(latlng) {
                if (!suggestMarker) {
                    suggestMarker = L.marker(latlng, { icon: suggestIcon, draggable: true }).addTo(suggestMap);
                    
                    suggestMarker.on('dragend', function(event) {
                        const marker = event.target;
                        const position = marker.getLatLng();
                        updateSuggestInputs(position);
                    });
                } else {
                    suggestMarker.setLatLng(latlng);
                }
                updateSuggestInputs(latlng);
            }
        }
        
        // Fix leaflet rendering bug in hidden containers
        setTimeout(() => {
            suggestMap.invalidateSize();
            if (suggestMarker) {
                suggestMap.setView(suggestMarker.getLatLng(), 14);
            } else {
                suggestMap.setView(mainMap.getCenter(), 14);
            }
        }, 100);
    }

    function updateSuggestInputs(latlng) {
        document.getElementById('coordX').value = latlng.lat.toFixed(6);
        document.getElementById('coordY').value = latlng.lng.toFixed(6);
    }

    function locateSuggestUser() {
        if (!suggestMap) return;
        suggestMap.locate({setView: true, maxZoom: 16});
        suggestMap.once('locationfound', function(e) {
            if (suggestMarker) {
                suggestMarker.setLatLng(e.latlng);
            }
            updateSuggestInputs(e.latlng);
        });
        suggestMap.once('locationerror', function(e) {
            alert("Could not find your location. Please check browser permissions.");
        });
    }

    function closeSuggestModal() {
        document.getElementById('suggestModal').classList.add('hidden');
    }

    // --- AJAX Join Logic ---
    function toggleJoinEvent(eventId) {
        fetch(`/events/${eventId}/join`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update all buttons for this event
                const buttons = document.querySelectorAll(`.join-btn-${eventId}`);
                buttons.forEach(btn => {
                    if (data.joined) {
                        if (btn.classList.contains('w-full')) {
                            // Popup button
                            btn.className = `join-btn-${eventId} w-full py-2 rounded-lg text-[11px] font-bold transition-all border bg-gray-100 text-gray-600 border-gray-200 hover:bg-red-50 hover:text-red-600 hover:border-red-200`;
                            btn.innerText = 'Joined (Click to Leave)';
                        } else {
                            // Sidebar button
                            btn.className = `join-btn-${eventId} px-3 py-1 rounded-md text-[10px] font-bold transition-all border bg-gray-100 text-gray-600 border-gray-200 hover:bg-red-50 hover:text-red-600 hover:border-red-200`;
                            btn.innerText = 'Joined';
                        }
                    } else {
                        if (btn.classList.contains('w-full')) {
                            // Popup button
                            btn.className = `join-btn-${eventId} w-full py-2 rounded-lg text-[11px] font-bold transition-all border bg-green-600 text-white border-green-600 hover:bg-green-700`;
                            btn.innerText = 'Join Event';
                        } else {
                            // Sidebar button
                            btn.className = `join-btn-${eventId} px-3 py-1 rounded-md text-[10px] font-bold transition-all border bg-green-50 text-green-700 border-green-200 hover:bg-green-600 hover:text-white hover:border-green-600`;
                            btn.innerText = 'Join';
                        }
                    }
                });

                // Update participant counts
                const countDisplaysMap = document.querySelectorAll(`[data-pin-id="${eventId}"] .event-participants-count, .leaflet-popup .event-participants-count`);
                const countDisplaysCard = document.querySelectorAll(`[data-card-id="${eventId}"] .event-participants-count`);
                
                // Note: The leaflet popup is dynamic, so we update the text content if it's currently open
                document.querySelectorAll('.leaflet-popup .event-participants-count').forEach(el => el.innerText = `${data.participantsCount} joining`);
                
                countDisplaysCard.forEach(el => el.innerText = `${data.participantsCount} participants`);
            }
        });
    }

    // --- Smart Map Title Logic ---
    let geocodeTimeout;

    // 1. Dynamic Viewport Stats & Reverse Geocoding
    mainMap.on('moveend', function() {
        updateVisibleEventsCount();
        
        // Reverse Geocoding (Debounced to prevent API spam)
        clearTimeout(geocodeTimeout);
        geocodeTimeout = setTimeout(() => {
            const center = mainMap.getCenter();
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${center.lat}&lon=${center.lng}&zoom=10`)
                .then(res => res.json())
                .then(data => {
                    if (data && data.address) {
                        let locationName = data.address.city || data.address.town || data.address.state || data.address.country || "Unknown Location";
                        if (data.address.country && locationName !== data.address.country) {
                            locationName += ", " + data.address.country;
                        }
                        document.getElementById('map-location-title').innerText = locationName;
                    } else {
                        document.getElementById('map-location-title').innerText = "Unknown Location";
                    }
                })
                .catch(err => console.error("Geocoding error:", err));
        }, 800);
    });

    function updateVisibleEventsCount() {
        const bounds = mainMap.getBounds();
        let visibleCount = 0;
        
        // Count how many markers are within bounds
        for (let eventId in markers) {
            if (bounds.contains(markers[eventId].getLatLng())) {
                visibleCount++;
            }
        }
        
        document.getElementById('map-visible-events').innerText = `${visibleCount} eco events nearby`;
    }

    // Call initially to set correct count for default bounds
    setTimeout(updateVisibleEventsCount, 500);

    // 2. Click-to-Search (Fly-To)
    function toggleSearch(showSearch) {
        const viewDiv = document.getElementById('map-title-view');
        const searchDiv = document.getElementById('map-search-view');
        const input = document.getElementById('map-search-input');

        if (showSearch) {
            viewDiv.classList.add('hidden');
            searchDiv.classList.remove('hidden');
            input.focus();
            input.value = '';
        } else {
            searchDiv.classList.add('hidden');
            viewDiv.classList.remove('hidden');
        }
    }

    document.getElementById('map-search-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            const query = this.value.trim();
            if (query) {
                document.getElementById('map-location-title').innerText = "Searching...";
                toggleSearch(false);
                
                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`)
                    .then(res => res.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            const result = data[0];
                            mainMap.flyTo([parseFloat(result.lat), parseFloat(result.lon)], 12, { duration: 1.5 });
                        } else {
                            alert("Location not found.");
                            document.getElementById('map-location-title').innerText = "Not Found";
                        }
                    })
                    .catch(err => console.error("Search error:", err));
            }
        }
    });
</script>
@endsection
