@extends('layouts.app')

@section('title', 'Admin — Submissions Review')

@section('content')
<div class="p-4 lg:p-6 max-w-5xl mx-auto space-y-5">

    {{-- Flash --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-2xl p-4 text-sm font-semibold text-green-800">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-2xl p-4 text-sm font-semibold text-red-700">{{ session('error') }}</div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-black text-gray-900">Submissions Under Review</h1>
            <p class="text-sm text-gray-500 mt-0.5">{{ $submissions->total() }} submissions need manual review</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-green-600 font-semibold hover:text-green-700">← Admin Home</a>
    </div>

    @forelse($submissions as $submission)
        @php
            $challenge   = $submission->challenge;
            $user        = $submission->user;
            $verifyCount = $submission->verifyCount();
            $reportCount = $submission->reportCount();
        @endphp
        <div class="bg-white rounded-2xl border border-orange-200 shadow-sm overflow-hidden">
            <div class="flex flex-col md:flex-row">

                {{-- Photo --}}
                <div class="md:w-64 flex-shrink-0">
                    @if($submission->photo_path)
                        <img src="{{ Storage::url($submission->photo_path) }}"
                             alt="Submission"
                             class="w-full h-48 md:h-full object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-100 flex items-center justify-center text-gray-400 text-sm">No photo</div>
                    @endif
                </div>

                {{-- Content --}}
                <div class="flex-1 p-5">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div>
                            <p class="text-base font-black text-gray-900">{{ $challenge->title }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">by <strong>{{ $user->name }}</strong> · {{ $submission->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="bg-orange-100 text-orange-700 text-xs font-bold px-3 py-1 rounded-full flex-shrink-0">Manual Review</span>
                    </div>

                    {{-- Stats --}}
                    <div class="grid grid-cols-3 gap-3 mb-4">
                        <div class="bg-green-50 rounded-xl p-3 text-center">
                            <p class="text-lg font-black text-green-700">{{ $verifyCount }}</p>
                            <p class="text-[10px] text-green-600">Verifies</p>
                        </div>
                        <div class="bg-red-50 rounded-xl p-3 text-center">
                            <p class="text-lg font-black text-red-700">{{ $reportCount }}</p>
                            <p class="text-[10px] text-red-600">Reports</p>
                        </div>
                        <div class="bg-blue-50 rounded-xl p-3 text-center">
                            <p class="text-lg font-black text-blue-700">{{ $submission->ai_score ?? '—' }}{{ $submission->ai_score ? '%' : '' }}</p>
                            <p class="text-[10px] text-blue-600">AI Score</p>
                        </div>
                    </div>

                    {{-- EXIF info --}}
                    @if($submission->exif_timestamp)
                        <p class="text-xs text-gray-400 mb-3">
                            Photo taken: {{ $submission->exif_timestamp->format('d M Y H:i') }}
                            @if($submission->exif_lat)
                                · GPS: {{ round($submission->exif_lat, 4) }}, {{ round($submission->exif_lng, 4) }}
                            @endif
                        </p>
                    @endif

                    {{-- AI labels --}}
                    @if($submission->ai_labels)
                        <div class="flex flex-wrap gap-1.5 mb-4">
                            @foreach($submission->ai_labels as $label)
                                <span class="text-[10px] bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ $label }}</span>
                            @endforeach
                        </div>
                    @endif

                    {{-- Actions --}}
                    <div class="flex gap-3">
                        <form action="{{ route('admin.submissions.approve', $submission) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-sm font-bold px-6 py-2.5 rounded-xl transition-colors flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                Approve (+{{ $challenge->points }} pts)
                            </button>
                        </form>
                        <form action="{{ route('admin.submissions.reject', $submission) }}" method="POST">
                            @csrf
                            <button type="submit"
                                onclick="return confirm('Reject this submission?')"
                                class="bg-red-100 hover:bg-red-200 text-red-700 text-sm font-bold px-6 py-2.5 rounded-xl transition-colors flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/></svg>
                                Reject
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-20">
            <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-gray-200 mx-auto mb-4"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <h3 class="text-lg font-bold text-gray-600 mb-2">No submissions under review</h3>
            <p class="text-sm text-gray-400">All clear! Nothing needs manual attention.</p>
        </div>
    @endforelse

    @if($submissions->hasPages())
        <div>{{ $submissions->links() }}</div>
    @endif
</div>
@endsection
