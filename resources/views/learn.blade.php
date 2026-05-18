@extends('layouts.app')

@section('title', 'Learn - TerraVerde')

@section('content')

<div class="p-4 lg:p-6 max-w-6xl mx-auto space-y-6">
    <!-- Header -->
    <form action="{{ route('learn') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400">
                <circle cx="11" cy="11" r="8"/>
                <path d="m21 21-4.3-4.3"/>
            </svg>
            <input
                name="search"
                value="{{ $search ?? '' }}"
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
    <div class="rounded-3xl p-6 text-white relative overflow-hidden animate-bounce-in shadow-lg" style="background: linear-gradient(135deg, #15803d 0%, #047857 45%, #0369a1 100%);">
        <div class="absolute inset-0 rounded-3xl" style="background-image: radial-gradient(circle at 15% 75%, rgba(52,211,153,0.18) 0%, transparent 55%), radial-gradient(circle at 85% 15%, rgba(56,189,248,0.15) 0%, transparent 55%);"></div>
        <div class="relative z-10 flex items-center gap-5">
            <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 2a14.5 14.5 0 0 0 0 20"/>
                    <path d="M2 12h20"/>
                </svg>
            </div>
            <div>
                <p class="text-green-200 text-xs font-semibold uppercase tracking-wide mb-1">United Nations SDG 13</p>
                <h2 class="text-xl font-black">Climate Action</h2>
                <p class="text-green-100 text-sm mt-1 max-w-lg">
                    Take urgent action to combat climate change and its impacts. Education is the first step — learn, act, inspire!
                </p>
            </div>
        </div>
    </div>

    <!-- Featured -->
    @if($featured)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden card-hover animate-count-in">
        <div class="grid grid-cols-1 lg:grid-cols-2">
            <div class="h-56 lg:h-auto overflow-hidden">
                <img 
                    src="{{ $featured->thumbnail 
                        ? asset('storage/' . $featured->thumbnail) 
                        : 'https://via.placeholder.com/400' }}"
                    loading="lazy"
                    decoding="async"
                    class="w-full h-full object-cover"
                />
            </div>
            <div class="p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-gray-100 text-gray-700">
                            {{ $featured->category }}
                        </span>
                        <span class="text-xs text-gray-400">Featured</span>
                    </div>
                    <h2 class="text-lg font-bold text-gray-900 mb-2">{{ $featured->title }}</h2>
                    <p class="text-sm text-gray-600 line-clamp-3">{{ $featured->excerpt }}</p>
                </div>
                <div class="mt-4 flex justify-between">
                   <a href="{{ route('learn.show', $featured->slug) }}"
                    class="bg-green-600 text-white text-xs px-4 py-2 rounded-xl">
                        Read
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- GRID -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($rest as $i => $article)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="h-44 overflow-hidden">
                <img 
                    src="{{ $article->thumbnail 
                        ? asset('storage/' . $article->thumbnail) 
                        : 'https://via.placeholder.com/400' }}"
                    loading="lazy"
                    decoding="async"
                    class="w-full h-full object-cover"
                />
            </div>
            <div class="p-4">
                <h3 class="text-sm font-bold mb-2">{{ $article->title }}</h3>
                <p class="text-xs text-gray-500 mb-3">{{ $article->excerpt }}</p>
                <div class="flex justify-between text-xs text-gray-400">
                    <span>{{ $article->category }}</span>
                    <span>by {{ $article->author->name ?? '-' }}</span>
                </div>
                <a href="{{ route('learn.show', $article->slug) }}"
                class="text-green-600 text-xs font-semibold">
                    Read →
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <!-- EMPTY -->
    @if($articles->count() === 0)
    <div class="text-center py-16">
        <p class="text-base font-bold text-gray-600">Tidak ada artikel saat ini</p>
    </div>
    @endif

</div>
@endsection