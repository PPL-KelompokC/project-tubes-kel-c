@extends('layouts.app')

@section('title', 'Activity Feed')

@section('content')
<div class="p-4 lg:p-6 max-w-3xl mx-auto space-y-5">

    {{-- Header --}}
    <div class="rounded-3xl p-6 text-white animate-bounce-in" style="background: linear-gradient(135deg, #15803d 0%, #047857 50%, #0369a1 100%);">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
            </div>
            <div>
                <h1 class="text-xl font-black">Activity Feed</h1>
                <p class="text-green-100 text-sm mt-0.5">Verified eco-actions from the community</p>
            </div>
        </div>
    </div>

    {{-- Verified submissions feed --}}
    @forelse($submissions as $i => $submission)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden animate-count-in" style="animation-delay: {{ $i * 0.05 }}s">

            {{-- Photo --}}
            @if($submission->photo_path)
                <div class="relative">
                    <img src="{{ Storage::url($submission->photo_path) }}"
                         alt="Submission photo"
                         class="w-full h-64 object-cover"
                         loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    <div class="absolute bottom-3 left-3">
                        <span class="bg-white/90 backdrop-blur text-xs font-bold text-gray-800 px-3 py-1.5 rounded-full">
                            {{ $submission->challenge->category }} · {{ $submission->challenge->difficulty }}
                        </span>
                    </div>
                    <div class="absolute bottom-3 right-3 flex items-center gap-1 bg-white/90 backdrop-blur rounded-full px-2.5 py-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="currentColor" class="text-yellow-500"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <span class="text-xs font-bold text-gray-800">+{{ $submission->points_awarded }} pts</span>
                    </div>
                    {{-- Verified badge --}}
                    <div class="absolute top-3 right-3 flex items-center gap-1.5 bg-green-500 text-white text-xs font-bold px-2.5 py-1 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        Verified
                    </div>
                </div>
            @endif

            {{-- Post body --}}
            <div class="p-4">
                <div class="flex items-center gap-3 mb-3">
                    @if($submission->user->avatar)
                        <img src="{{ $submission->user->avatar }}" class="w-9 h-9 rounded-full object-cover" alt="">
                    @else
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                            {{ substr($submission->user->name, 0, 1) }}
                        </div>
                    @endif
                    <div class="flex-1">
                        <p class="text-sm font-bold text-gray-900">{{ $submission->user->name }}</p>
                        <p class="text-xs text-gray-400">{{ $submission->verified_at?->diffForHumans() }}</p>
                    </div>
                    {{-- CO2 saved badge --}}
                    <div class="flex items-center gap-1 bg-green-50 border border-green-100 px-2.5 py-1 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
                        <span class="text-[10px] font-bold text-green-700">{{ $submission->challenge->co2_saved }}kg CO₂</span>
                    </div>
                </div>

                <p class="text-base font-black text-gray-900 mb-1">{{ $submission->challenge->title }}</p>
                <p class="text-xs text-gray-500">{{ $submission->challenge->description }}</p>

                {{-- AI score --}}
                @if($submission->ai_score)
                    <div class="mt-3 text-[10px] text-gray-400 flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/></svg>
                        AI confidence: {{ $submission->ai_score }}%
                        @if($submission->ai_labels)
                            · {{ implode(', ', array_slice($submission->ai_labels, 0, 3)) }}
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="text-center py-20">
            <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-gray-200 mx-auto mb-4"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
            <h3 class="text-lg font-bold text-gray-600 mb-2">No verified actions yet</h3>
            <p class="text-sm text-gray-400">Complete a challenge — once admin approves, it appears here!</p>
            <a href="{{ route('challenges') }}" class="inline-block mt-5 bg-green-600 text-white px-6 py-3 rounded-xl text-sm font-semibold hover:bg-green-700 transition-colors">
                View Challenges
            </a>
        </div>
    @endforelse

    @if($submissions->hasPages())
        <div class="pt-2">{{ $submissions->links() }}</div>
    @endif
</div>
@endsection
