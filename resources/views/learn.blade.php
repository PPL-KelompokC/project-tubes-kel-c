@extends('layouts.app')

@section('title', 'Learn - EcoChallenge')

@section('content')
@php
    $articles = [
        [
            'id' => 1,
            'title' => 'The Hidden Cost of Fast Fashion: How Your Wardrobe Affects the Climate',
            'excerpt' => 'The fashion industry produces 10% of global carbon emissions. Here\'s what you can do to make your wardrobe more sustainable.',
            'image' => 'https://images.unsplash.com/photo-1704793027965-da6e888e89fd?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&w=400',
            'category' => 'Education',
            'readTime' => '5 min read',
            'author' => 'Dr. Emma Green',
            'featured' => true,
            'tags' => ['fashion', 'carbon', 'sustainability'],
        ],
        [
            'id' => 2,
            'title' => 'Why Plant-Based Diets Are the Most Impactful Climate Choice You Can Make',
            'excerpt' => 'Switching to a plant-based diet can reduce your personal carbon footprint by up to 73%. We explain the science behind it.',
            'image' => 'https://images.unsplash.com/photo-1770914755925-6468b9050176?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&w=400',
            'category' => 'Lifestyle',
            'readTime' => '7 min read',
            'author' => 'James Okafor',
            'featured' => false,
            'tags' => ['food', 'diet', 'emissions'],
        ],
        [
            'id' => 3,
            'title' => 'SDG 13 Explained: What Climate Action Actually Means in 2024',
            'excerpt' => 'The United Nations SDG 13 calls for urgent action to combat climate change. Here\'s a breakdown of what needs to happen.',
            'image' => 'https://images.unsplash.com/photo-1645307356404-407a1083ec59?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&w=400',
            'category' => 'SDG 13',
            'readTime' => '8 min read',
            'author' => 'Climate Action Team',
            'featured' => false,
            'tags' => ['sdg', 'policy', 'global'],
        ],
    ];

    $articleCategories = ['All', 'SDG 13', 'Energy', 'Transport', 'Education', 'Lifestyle', 'Environment'];

    $categoryColors = [
        'SDG 13' => 'bg-green-100 text-green-700',
        'Energy' => 'bg-yellow-100 text-yellow-700',
        'Transport' => 'bg-blue-100 text-blue-700',
        'Education' => 'bg-purple-100 text-purple-700',
        'Lifestyle' => 'bg-pink-100 text-pink-700',
        'Environment' => 'bg-teal-100 text-teal-700',
    ];

    $selectedCategory = request('category', 'All');
    $search = request('search');

    $filtered = collect($articles)->filter(function($a) use ($selectedCategory, $search) {
        if ($selectedCategory !== 'All' && $a['category'] !== $selectedCategory) return false;
        if ($search && stripos($a['title'], $search) === false && stripos($a['excerpt'], $search) === false) return false;
        return true;
    });

    $featured = $filtered->firstWhere('featured', true) ?: $filtered->first();
    $rest = $filtered->filter(fn($a) => $a['id'] !== ($featured['id'] ?? null));
@endphp

<div class="p-4 lg:p-6 max-w-6xl mx-auto space-y-6">
    <!-- Header -->
    <form action="{{ route('learn') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <input
                name="search"
                value="{{ $search }}"
                placeholder="Search articles..."
                class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-300 shadow-sm"
            />
        </div>
    </form>

    <!-- Category filter -->
    <div class="flex gap-2 overflow-x-auto pb-1 hide-scrollbar">
        @foreach($articleCategories as $cat)
            <a
                href="{{ route('learn', ['category' => $cat]) }}"
                class="flex-shrink-0 px-4 py-2 rounded-full text-xs font-semibold transition-all border {{ $selectedCategory === $cat ? 'bg-green-600 text-white border-green-600' : 'bg-white text-gray-600 border-gray-200 hover:border-green-300' }}"
            >
                {{ $cat }}
            </a>
        @endforeach
    </div>

    <!-- SDG 13 Banner -->
    <div class="eco-gradient rounded-3xl p-6 text-white eco-pattern flex items-center gap-5 animate-bounce-in">
        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
        </div>
        <div>
            <p class="text-green-200 text-xs font-semibold uppercase tracking-wide mb-1">United Nations SDG 13</p>
            <h2 class="text-xl font-black">Climate Action</h2>
            <p class="text-green-100 text-sm mt-1 max-w-lg">
                Take urgent action to combat climate change and its impacts. Education is the first step — learn, act, inspire!
            </p>
        </div>
    </div>

    <!-- Featured article -->
    @if($featured)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden card-hover animate-count-in">
            <div class="grid grid-cols-1 lg:grid-cols-2">
                <div class="h-56 lg:h-auto overflow-hidden">
                    <img src="{{ $featured['image'] }}" alt="{{ $featured['title'] }}" class="w-full h-full object-cover" />
                </div>
                <div class="p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $categoryColors[$featured['category']] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $featured['category'] }}
                            </span>
                            <span class="text-xs text-gray-400">Featured</span>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900 leading-tight mb-2">{{ $featured['title'] }}</h2>
                        <p class="text-sm text-gray-600 line-clamp-3">{{ $featured['excerpt'] }}</p>
                    </div>
                    <div class="mt-4 flex items-center justify-between">
                        <div class="flex items-center gap-3 text-xs text-gray-500">
                            <div class="flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>{{ $featured['readTime'] }}</div>
                            <span>by {{ $featured['author'] }}</span>
                        </div>
                        <button class="bg-green-600 hover:bg-green-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition-colors flex items-center gap-1.5">
                            Read <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" x2="21" y1="14" y2="3"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Article grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($rest as $i => $article)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden card-hover animate-count-in" style="animation-delay: {{ $i * 0.08 }}s">
                <div class="h-44 overflow-hidden relative">
                    <img src="{{ $article['image'] }}" alt="{{ $article['title'] }}" class="w-full h-full object-cover" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                    <div class="absolute top-3 left-3">
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $categoryColors[$article['category']] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ $article['category'] }}
                        </span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="text-sm font-bold text-gray-900 leading-tight mb-2 line-clamp-2">{{ $article['title'] }}</h3>
                    <p class="text-xs text-gray-500 line-clamp-2 mb-3">{{ $article['excerpt'] }}</p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-[10px] text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            {{ $article['readTime'] }}
                        </div>
                        <button class="text-green-600 hover:text-green-700 text-xs font-bold flex items-center gap-1">
                            Read <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" x2="21" y1="14" y2="3"/></svg>
                        </button>
                    </div>
                    <div class="mt-2 flex gap-1 flex-wrap">
                        @foreach($article['tags'] as $tag)
                            <span class="text-[10px] bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">
                                #{{ $tag }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($filtered->count() === 0)
        <div class="text-center py-16">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-gray-200 mx-auto mb-3"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/></svg>
            <p class="text-base font-bold text-gray-600">No articles found</p>
            <p class="text-sm text-gray-400">Try a different search or category</p>
        </div>
    @endif

    <!-- Learning path CTA -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        @foreach([
            ['svgPath' => '<path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>', 'iconColor' => 'text-green-600', 'title' => 'Beginner Path', 'desc' => 'Start your climate journey with basics', 'color' => 'from-green-50 to-emerald-50 border-green-100'],
            ['svgPath' => '<path d="M12 2a10 10 0 0 1 10 10c0 5.52-4.48 10-10 10S2 17.52 2 12 6.48 2 12 2Z"/><path d="M12 6v6l4 2"/>', 'iconColor' => 'text-blue-600', 'title' => 'Intermediate Path', 'desc' => 'Deepen your understanding of climate science', 'color' => 'from-blue-50 to-indigo-50 border-blue-100'],
            ['svgPath' => '<path d="M17 14c.83-1.071 1.5-2.547 1.5-4.5C18.5 5.686 15.314 3 12 3S5.5 5.686 5.5 9.5c0 1.953.67 3.429 1.5 4.5"/><path d="M12 3v11"/><path d="M9 21h6"/><path d="M12 16v5"/>', 'iconColor' => 'text-purple-600', 'title' => 'Expert Path', 'desc' => 'Master sustainability and climate policy', 'color' => 'from-purple-50 to-pink-50 border-purple-100'],
        ] as $path)
            <div class="bg-gradient-to-br {{ $path['color'] }} border rounded-2xl p-4 text-center hover:shadow-md transition-all cursor-pointer">
                <div class="w-12 h-12 rounded-2xl bg-white/60 flex items-center justify-center mx-auto mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ $path['iconColor'] }}">{!! $path['svgPath'] !!}</svg>
                </div>
                <p class="text-sm font-bold text-gray-900">{{ $path['title'] }}</p>
                <p class="text-xs text-gray-600 mt-1">{{ $path['desc'] }}</p>
                <button class="mt-3 text-green-600 text-xs font-bold hover:text-green-700">Start →</button>
            </div>
        @endforeach
    </div>
</div>
@endsection
