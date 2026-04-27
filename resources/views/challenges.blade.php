@extends('layouts.app')

@section('title', 'Challenges - ClimateTracker')

@section('content')
@php
    $categories = [
        ['id' => 'transport', 'name' => 'Transport', 'bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'border-blue-100', 'svgPath' => '<path d="M5 17H3a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v3"/><rect width="7" height="7" x="14" y="10" rx="1"/><circle cx="7.5" cy="17.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/>'],
        ['id' => 'energy', 'name' => 'Energy', 'bg' => 'bg-orange-50', 'text' => 'text-orange-600', 'border' => 'border-orange-100', 'svgPath' => '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>'],
        ['id' => 'food', 'name' => 'Food', 'bg' => 'bg-green-50', 'text' => 'text-green-600', 'border' => 'border-green-100', 'svgPath' => '<path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>'],
        ['id' => 'water', 'name' => 'Water', 'bg' => 'bg-cyan-50', 'text' => 'text-cyan-600', 'border' => 'border-cyan-100', 'svgPath' => '<path d="M12 22a7 7 0 0 0 7-7c0-2-1-3.9-3-5.5s-3.5-4-4-6.5c-.5 2.5-2 4.9-4 6.5C6 11.1 5 13 5 15a7 7 0 0 0 7 7z"/>'],
        ['id' => 'waste', 'name' => 'Waste', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'border' => 'border-emerald-100', 'svgPath' => '<path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="m16 5-3 3-3-3"/><path d="m8 11 3 3"/><path d="m14 11-3 3"/>'],
        ['id' => 'nature', 'name' => 'Nature', 'bg' => 'bg-green-50', 'text' => 'text-green-600', 'border' => 'border-green-100', 'svgPath' => '<path d="M17 14c.83-1.071 1.5-2.547 1.5-4.5C18.5 5.686 15.314 3 12 3S5.5 5.686 5.5 9.5c0 1.953.67 3.429 1.5 4.5"/><path d="M12 3v11"/><path d="M9 21h6"/><path d="M12 16v5"/>'],
    ];

    $difficultyColor = [
        'easy' => 'bg-green-100 text-green-700',
        'medium' => 'bg-yellow-100 text-yellow-700',
        'hard' => 'bg-red-100 text-red-700',
    ];

    $selectedCategory = request('category', 'all');
    $search = request('search');
    $currentSort = request('sort', 'newest');
    $currentDifficulty = request('difficulty');
    $currentStatus = request('status');
    $currentPointsMin = request('points_min');
    $currentPointsMax = request('points_max');
    $currentCo2Min = request('co2_min');
    $currentCo2Max = request('co2_max');

    $filteredChallenges = $challenges; // Already filtered by controller
@endphp

<div class="p-4 lg:p-8 max-w-7xl mx-auto space-y-8">
    <!-- Header: Search, Filters, Sort -->
    <form id="filterForm" action="{{ route('challenges') }}" method="GET" class="flex flex-col lg:flex-row gap-4">
        <!-- Preserve other filters when searching or sorting -->
        <input type="hidden" name="category" value="{{ $selectedCategory }}">
        <input type="hidden" name="difficulty" value="{{ $currentDifficulty }}">
        <input type="hidden" name="status" value="{{ $currentStatus }}">
        <input type="hidden" name="points_min" value="{{ $currentPointsMin }}">
        <input type="hidden" name="points_max" value="{{ $currentPointsMax }}">
        <input type="hidden" name="co2_min" value="{{ $currentCo2Min }}">
        <input type="hidden" name="co2_max" value="{{ $currentCo2Max }}">

        <div class="relative flex-1">
            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            </div>
            <input
                type="text"
                name="search"
                id="searchInput"
                value="{{ $search }}"
                placeholder="Search challenges..."
                class="w-full pl-12 pr-4 py-3.5 bg-white border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-green-100 shadow-sm transition-all"
            />
        </div>
        
        <div class="flex gap-2">
            <button type="button" onclick="toggleFilterModal()" class="flex items-center gap-2 px-6 py-3.5 bg-white border border-gray-100 rounded-2xl text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="10" y1="21" y2="21"/><line x1="14" x2="20" y1="21" y2="21"/><path d="M15 15a3 3 0 1 0-6 0"/><path d="M12 12V3"/><path d="M12 21v-3"/><path d="M9 12h6"/></svg>
                Filters
                @if($currentDifficulty || $currentStatus || $currentPointsMin || $currentPointsMax || $currentCo2Min || $currentCo2Max)
                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                @endif
            </button>

            <div class="relative min-w-[180px]">
                <select name="sort" onchange="this.form.submit()" class="w-full appearance-none px-6 py-3.5 bg-white border border-gray-100 rounded-2xl text-sm font-semibold text-gray-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-green-100">
                    <option value="newest" {{ $currentSort == 'newest' ? 'selected' : '' }}>Sort: Newest</option>
                    <option value="points_high" {{ $currentSort == 'points_high' ? 'selected' : '' }}>Sort: Points (High)</option>
                    <option value="points_low" {{ $currentSort == 'points_low' ? 'selected' : '' }}>Sort: Points (Low)</option>
                    <option value="co2_high" {{ $currentSort == 'co2_high' ? 'selected' : '' }}>Sort: CO₂ Impact (High)</option>
                    <option value="co2_low" {{ $currentSort == 'co2_low' ? 'selected' : '' }}>Sort: CO₂ Impact (Low)</option>
                    <option value="popular" {{ $currentSort == 'popular' ? 'selected' : '' }}>Sort: Popular</option>
                </select>
                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </div>
            </div>
        </div>
    </form>

    <!-- Category quick pills -->
    <div class="flex gap-3 overflow-x-auto pb-2 hide-scrollbar">
        <a href="{{ route('challenges', array_merge(request()->query(), ['category' => 'all'])) }}" 
           class="flex-shrink-0 flex items-center gap-2 px-6 py-2.5 rounded-full text-sm font-bold {{ $selectedCategory == 'all' ? 'bg-green-600 text-white shadow-md shadow-green-100' : 'bg-white text-gray-600 border border-gray-100 hover:border-green-300 hover:text-green-600' }} transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg> All
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('challenges', array_merge(request()->query(), ['category' => $cat['id']])) }}" 
               class="flex-shrink-0 flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-bold {{ $selectedCategory == $cat['id'] ? 'bg-green-600 text-white shadow-md shadow-green-100' : 'bg-white text-gray-600 border border-gray-100 hover:border-green-300 hover:text-green-600' }} transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ $selectedCategory == $cat['id'] ? 'text-white' : $cat['text'] }}">{!! $cat['svgPath'] !!}</svg> {{ $cat['name'] }}
            </a>
        @endforeach
    </div>

    <!-- Results count -->
    <div class="flex items-center justify-between">
        <h2 class="text-base font-bold text-gray-900">
            {{ count($filteredChallenges) }} <span class="text-gray-400 font-medium ml-1">challenges found</span>
        </h2>
        <span class="text-xs text-gray-400 font-medium">
            {{ collect($challenges)->where('status', 'completed')->count() }} completed
        </span>
    </div>

    <!-- Challenge grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($filteredChallenges as $i => $challenge)
            @php 
                $isCompleted = $challenge['status'] === 'completed';
                $cat = collect($categories)->firstWhere('id', $challenge['category']) ?? [
                    'bg' => 'bg-gray-50', 'text' => 'text-gray-600', 'border' => 'border-gray-100'
                ];
            @endphp
            <div class="group bg-white rounded-[32px] border border-gray-100 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1 animate-bounce-in" style="animation-delay: {{ $i * 0.05 }}s">
                <!-- Card Image -->
                <div class="relative h-56 overflow-hidden">
                    <img src="{{ $challenge['imageUrl'] }}" alt="{{ $challenge['title'] }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
                    
                    <!-- Badges on Image -->
                    <div class="absolute top-4 left-4">
                        <span class="text-[10px] font-bold px-3 py-1.5 rounded-full {{ $cat['bg'] }} {{ $cat['text'] }} backdrop-blur-md flex items-center gap-1.5 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
                            {{ ucfirst($challenge['category']) }}
                        </span>
                    </div>

                    <div class="absolute top-4 right-4">
                        <span class="text-[10px] font-bold px-3 py-1.5 rounded-full {{ $difficultyColor[$challenge['difficulty']] ?? 'bg-gray-100 text-gray-700' }} backdrop-blur-md shadow-sm">
                            {{ ucfirst($challenge['difficulty']) }}
                        </span>
                    </div>

                    @if($isCompleted)
                        <div class="absolute inset-0 bg-black/10 backdrop-blur-[1px] flex items-center justify-center">
                            <div class="bg-green-500 text-white px-5 py-2.5 rounded-full text-xs font-bold flex items-center gap-2 shadow-lg scale-110">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg> Done!
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Card Content -->
                <div class="p-6 space-y-5">
                    <div class="space-y-2">
                        <h3 class="text-lg font-bold text-gray-900 leading-tight group-hover:text-green-700 transition-colors">{{ $challenge['title'] }}</h3>
                        <p class="text-xs text-gray-500 leading-relaxed line-clamp-2">{{ $challenge['description'] }}</p>
                    </div>

                    <!-- Stats Row -->
                    <div class="flex items-center gap-5 text-xs font-bold text-gray-700">
                        <div class="flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" class="text-yellow-500"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            {{ $challenge['points'] }} pts
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-500"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
                            {{ $challenge['co2Saved'] }}kg CO₂
                        </div>
                        <div class="flex items-center gap-1.5 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            {{ number_format($challenge['participants']) }}
                        </div>
                    </div>

                    <!-- Tip Box -->
                    <div class="bg-amber-50/50 border border-amber-100/50 rounded-2xl p-3.5 flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-500 flex-shrink-0 mt-0.5"><path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"/><path d="M9 18h6"/><path d="M10 22h4"/></svg>
                        <p class="text-[10px] text-amber-800 leading-relaxed font-semibold">{{ $challenge['impact'] }}</p>
                    </div>

                    <!-- Button -->
                    @if($isCompleted)
                        <div class="w-full bg-green-500 text-white font-bold py-4 rounded-2xl text-sm shadow-lg shadow-green-100 flex items-center justify-center gap-2 cursor-default">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            Done!
                        </div>
                    @else
                        <a href="{{ route('challenges.submit', $challenge['id']) }}" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-2xl text-sm transition-all active:scale-[0.98] flex items-center justify-center gap-2 shadow-lg shadow-green-50 hover:shadow-green-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            Mark Complete
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    @if(count($filteredChallenges) === 0)
        <div class="text-center py-24 bg-white rounded-[40px] border border-gray-100 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-gray-200 mx-auto mb-6"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <h3 class="text-xl font-bold text-gray-800 mb-2">No challenges found</h3>
            <p class="text-sm text-gray-500 mb-8">Try adjusting your filters or search term</p>
            <a href="{{ route('challenges') }}" class="inline-block bg-green-600 text-white px-10 py-4 rounded-2xl text-sm font-bold hover:bg-green-700 transition-all shadow-lg shadow-green-100">
                Clear all filters
            </a>
        </div>
    @endif

    <!-- Filter Modal -->
    <div id="filterModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="toggleFilterModal()"></div>
        <div class="absolute right-0 top-0 bottom-0 w-full max-w-md bg-white shadow-2xl animate-slide-in-right p-8 overflow-y-auto">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-bold text-gray-900">Filters</h3>
                <button onclick="toggleFilterModal()" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>

            <form action="{{ route('challenges') }}" method="GET" class="space-y-8">
                <input type="hidden" name="search" value="{{ $search }}">
                <input type="hidden" name="category" value="{{ $selectedCategory }}">
                <input type="hidden" name="sort" value="{{ $currentSort }}">

                <!-- Difficulty -->
                <div class="space-y-4">
                    <label class="text-sm font-bold text-gray-900">Difficulty</label>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach(['easy', 'medium', 'hard'] as $diff)
                            <label class="cursor-pointer group">
                                <input type="radio" name="difficulty" value="{{ $diff }}" class="hidden peer" {{ $currentDifficulty == $diff ? 'checked' : '' }}>
                                <div class="px-4 py-2.5 rounded-xl border border-gray-100 text-center text-xs font-bold transition-all peer-checked:bg-green-600 peer-checked:text-white peer-checked:border-green-600 group-hover:border-green-200">
                                    {{ ucfirst($diff) }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Points Range -->
                <div class="space-y-4">
                    <label class="text-sm font-bold text-gray-900">Points Range</label>
                    <div class="flex gap-4 items-center">
                        <input type="number" name="points_min" value="{{ $currentPointsMin }}" placeholder="Min" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-100">
                        <span class="text-gray-400">-</span>
                        <input type="number" name="points_max" value="{{ $currentPointsMax }}" placeholder="Max" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-100">
                    </div>
                </div>

                <!-- CO2 Range -->
                <div class="space-y-4">
                    <label class="text-sm font-bold text-gray-900">CO₂ Saved (kg)</label>
                    <div class="flex gap-4 items-center">
                        <input type="number" name="co2_min" value="{{ $currentCo2Min }}" placeholder="Min" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-100">
                        <span class="text-gray-400">-</span>
                        <input type="number" name="co2_max" value="{{ $currentCo2Max }}" placeholder="Max" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-100">
                    </div>
                </div>

                <!-- Status -->
                <div class="space-y-4">
                    <label class="text-sm font-bold text-gray-900">Status</label>
                    <div class="space-y-3">
                        @foreach(['all' => 'All Status', 'completed' => 'Completed', 'uncompleted' => 'Not Completed'] as $val => $label)
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="status" value="{{ $val }}" class="w-5 h-5 border-gray-300 text-green-600 focus:ring-green-100" {{ ($currentStatus ?? 'all') == $val ? 'checked' : '' }}>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-green-600 transition-colors">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="pt-8 flex gap-4">
                    <a href="{{ route('challenges', ['category' => $selectedCategory, 'search' => $search, 'sort' => $currentSort]) }}" class="flex-1 px-6 py-4 rounded-2xl border border-gray-100 text-sm font-bold text-gray-500 hover:bg-gray-50 text-center transition-all">
                        Reset
                    </a>
                    <button type="submit" class="flex-1 px-6 py-4 rounded-2xl bg-green-600 text-white text-sm font-bold hover:bg-green-700 shadow-lg shadow-green-100 transition-all">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleFilterModal() {
        const modal = document.getElementById('filterModal');
        modal.classList.toggle('hidden');
        if (!modal.classList.contains('hidden')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = 'auto';
        }
    }

    // Real-time search with debounce
    let searchTimeout;
    const searchInput = document.getElementById('searchInput');
    const filterForm = document.getElementById('filterForm');

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            filterForm.submit();
        }, 600); // 600ms debounce
    });

    // Set cursor to end of search input if there's a value
    if (searchInput.value) {
        searchInput.focus();
        const val = searchInput.value;
        searchInput.value = '';
        searchInput.value = val;
    }
</script>
<style>
    @keyframes slide-in-right {
        from { transform: translateX(100%); }
        to { transform: translateX(0); }
    }
    .animate-slide-in-right {
        animation: slide-in-right 0.3s ease-out forwards;
    }
</style>
@endpush
@endsection
