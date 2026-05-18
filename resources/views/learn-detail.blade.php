@extends('layouts.app')

@section('title', $article->title)

@section('content')

<div class="max-w-4xl mx-auto p-4 lg:p-6">

    <!-- Back -->
    <a href="{{ route('learn') }}" class="text-sm text-gray-500 hover:text-green-600">
        ← Back to articles
    </a>

    <!-- Thumbnail -->
    <div class="mt-4 rounded-2xl overflow-hidden">
        <img 
            src="{{ $article->thumbnail 
                ? asset('storage/' . $article->thumbnail) 
                : 'https://via.placeholder.com/800x400' }}"
            class="w-full h-72 object-cover"
        />
    </div>

    <!-- Meta -->
    <div class="mt-6">
        <span class="text-xs px-3 py-1 bg-gray-100 rounded-full">
            {{ $article->category }}
        </span>

        <h1 class="text-2xl lg:text-3xl font-bold mt-3">
            {{ $article->title }}
        </h1>

        <p class="text-sm text-gray-500 mt-2">
            by {{ $article->author->name ?? 'Unknown' }}
        </p>
    </div>

    <!-- Content -->
    <div class="mt-6 text-gray-700 leading-relaxed space-y-4">
        {!! nl2br(e($article->content)) !!}
    </div>

</div>

@endsection