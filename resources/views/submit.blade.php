@extends('layouts.app')

@section('title', 'Submit Challenge')

@section('content')
<div class="p-4 lg:p-6 max-w-2xl mx-auto space-y-6">

    {{-- Flash messages --}}
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-2xl p-4 flex items-start gap-3 animate-count-in">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-500 flex-shrink-0 mt-0.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
            <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Header --}}
    <div class="rounded-3xl p-6 text-white relative overflow-hidden animate-bounce-in" style="background: linear-gradient(135deg, #15803d 0%, #047857 50%, #0369a1 100%);">
        <div class="relative z-10">
            <p class="text-green-200 text-xs font-semibold uppercase tracking-wide mb-1">Submit Proof</p>
            <h1 class="text-xl font-black leading-tight">{{ $challenge->title }}</h1>
            <div class="flex items-center gap-3 mt-3">
                <span class="bg-white/20 text-white text-xs font-semibold px-3 py-1 rounded-full">{{ $challenge->category }}</span>
                <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $challenge->difficulty === 'Easy' ? 'bg-green-400/30 text-green-100' : ($challenge->difficulty === 'Hard' ? 'bg-red-400/30 text-red-100' : 'bg-yellow-400/30 text-yellow-100') }}">{{ $challenge->difficulty }}</span>
                <div class="flex items-center gap-1 ml-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" class="text-yellow-300"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <span class="text-white font-bold text-sm">{{ $challenge->points }} pts</span>
                </div>
            </div>
        </div>
        <div class="absolute -right-8 -top-8 w-32 h-32 rounded-full bg-white/5"></div>
    </div>

    {{-- Already submitted (Verified or Pending) --}}
    @if($existingSubmission && $existingSubmission->status !== 'rejected')
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 text-center animate-count-in">
            <div class="w-16 h-16 rounded-full bg-green-50 flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-500"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <h2 class="text-lg font-bold text-gray-900 mb-2">Already Submitted!</h2>
            <p class="text-sm text-gray-500 mb-1">Your proof was submitted today.</p>
            <span class="inline-block text-xs font-bold px-3 py-1.5 rounded-full {{ $existingSubmission->statusColor() }}">
                {{ $existingSubmission->statusLabel() }}
            </span>

            <div class="mt-5">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-xl transition-colors text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                    Back to Dashboard
                </a>
            </div>
        </div>
    @else
        {{-- Submit form (New or Rejected) --}}
        @if($existingSubmission && $existingSubmission->status === 'rejected')
            <div class="bg-red-50 border border-red-200 rounded-2xl p-4 flex items-start gap-3 animate-count-in">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-500 flex-shrink-0 mt-0.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                <div>
                    <p class="text-sm font-bold text-red-800 mb-0.5">Submission Rejected</p>
                    <p class="text-xs text-red-700">Your previous photo was not accepted. Reason: <strong>"{{ $existingSubmission->rejection_reason ?? 'No reason provided' }}"</strong></p>
                    <p class="text-xs text-red-600 mt-1 italic">Please take a better photo and try again.</p>
                </div>
            </div>
        @endif

        <form action="{{ route('challenges.submit.store', $challenge) }}" method="POST" enctype="multipart/form-data" id="submitForm">
            @csrf

            {{-- Instructions card --}}
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-start gap-3 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-500 flex-shrink-0 mt-0.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
                <div>
                    <p class="text-sm font-bold text-amber-800 mb-1">Photo Requirements</p>
                    <ul class="text-xs text-amber-700 space-y-0.5">
                        <li>• Must be taken <strong>NOW</strong> using your camera (no gallery)</li>
                        <li>• Photo must clearly show your eco action</li>
                        <li>• Will be manually verified by our admins</li>
                    </ul>
                </div>
            </div>

            {{-- Challenge description --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-4">
                <h3 class="text-sm font-bold text-gray-900 mb-2">What to photograph:</h3>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $challenge->description }}</p>
            </div>

            {{-- Camera capture --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-4">
                <p class="text-sm font-bold text-gray-900 mb-3">Take Your Photo</p>

                {{-- Preview --}}
                <div id="previewContainer" class="hidden mb-4">
                    <img id="previewImg" src="" alt="Preview" class="w-full rounded-xl object-cover max-h-72">
                    <button type="button" id="retakeBtn"
                        class="mt-2 text-xs text-gray-500 hover:text-red-600 flex items-center gap-1 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                        Retake photo
                    </button>
                </div>

                {{-- Camera button (LAYER 1: camera only, no gallery) --}}
                <label id="cameraLabel"
                    class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-green-300 rounded-xl cursor-pointer bg-green-50 hover:bg-green-100 transition-colors group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-green-400 mb-2 group-hover:text-green-600 transition-colors"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                    <p class="text-sm font-semibold text-green-700 group-hover:text-green-800">Tap to open camera</p>
                    <p class="text-xs text-green-500 mt-0.5">Camera only — no gallery upload</p>
                    {{-- Layer 1: capture="environment" forces camera, not gallery --}}
                    <input type="file" name="photo" id="photoInput"
                        accept="image/*" capture="environment"
                        class="hidden" required>
                </label>

                @error('photo')
                    <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit button --}}
            <button type="submit" id="submitBtn"
                class="w-full py-4 bg-green-600 hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold text-base rounded-2xl transition-all flex items-center justify-center gap-3 shadow-lg shadow-green-200">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                <span id="submitBtnText">Submit Proof for Verification</span>
            </button>

            {{-- Loading state --}}
            <div id="loadingState" class="hidden mt-4 bg-blue-50 border border-blue-200 rounded-2xl p-4 text-center">
                <div class="flex items-center justify-center gap-3">
                    <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    <p class="text-sm font-semibold text-blue-700">Uploading… Please wait.</p>
                </div>
            </div>
        </form>

        {{-- Points info --}}
        <div class="grid grid-cols-3 gap-3">
            @foreach([
                ['icon' => '<path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/>', 'color' => 'text-yellow-500', 'value' => $challenge->points . ' pts', 'label' => 'On verify'],
                ['icon' => '<path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>', 'color' => 'text-green-500', 'value' => $challenge->co2_saved . ' kg', 'label' => 'CO₂ saved'],
                ['icon' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>', 'color' => 'text-blue-500', 'value' => $challenge->participantCount() . ' joined', 'label' => 'Today'],
            ] as $stat)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-1 {{ $stat['color'] }}">{!! $stat['icon'] !!}</svg>
                    <p class="text-base font-black text-gray-900">{{ $stat['value'] }}</p>
                    <p class="text-[10px] text-gray-400">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
(function () {
    const input     = document.getElementById('photoInput');
    const label     = document.getElementById('cameraLabel');
    const preview   = document.getElementById('previewContainer');
    const previewImg= document.getElementById('previewImg');
    const retakeBtn = document.getElementById('retakeBtn');
    const submitBtn = document.getElementById('submitBtn');
    const form      = document.getElementById('submitForm');
    const loading   = document.getElementById('loadingState');
    const btnText   = document.getElementById('submitBtnText');

    if (!input) return;

    input.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = (e) => {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
            label.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    });

    retakeBtn?.addEventListener('click', function () {
        input.value = '';
        preview.classList.add('hidden');
        label.classList.remove('hidden');
        previewImg.src = '';
    });

    form?.addEventListener('submit', function () {
        submitBtn.disabled = true;
        btnText.textContent = 'Uploading…';
        loading.classList.remove('hidden');
    });
})();
</script>
@endsection
