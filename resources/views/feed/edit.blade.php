@extends('layouts.app')

@section('title', 'Edit Post - TerraVerde')

@section('content')
<div class="p-4 lg:p-6 max-w-2xl mx-auto">
    <!-- Header -->
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('feed') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Edit Post</h1>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                </svg>
                <div>
                    <h3 class="font-bold text-red-900 mb-2">Validation Errors:</h3>
                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Form -->
    <form action="{{ route('feed.update', $feed) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
        @csrf
        @method('PATCH')

        <!-- Original Author Info -->
        <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
            @if($feed->user->avatar)
                <img src="{{ $feed->user->avatar }}" alt="{{ $feed->user->name }}" class="w-10 h-10 rounded-full object-cover ring-2 ring-green-200" />
            @else
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white font-bold text-sm">
                    {{ substr($feed->user->name, 0, 1) }}
                </div>
            @endif
            <div>
                <p class="font-bold text-gray-900">{{ $feed->user->name }}</p>
                <p class="text-sm text-gray-500">Editing on {{ now()->format('M d, Y') }}</p>
            </div>
        </div>

        <!-- Caption -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">What's on your mind?</label>
            <textarea
                name="caption"
                placeholder="Share your eco action... 🌱"
                class="w-full text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 resize-none focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-green-400 @error('caption') border-red-400 ring-red-300 @enderror"
                rows="4"
                required
            >{{ old('caption', $feed->caption) }}</textarea>
            @error('caption')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Current Media Display -->
        @if($feed->media && is_array($feed->media) && count($feed->media) > 0)
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Current Media</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($feed->media as $index => $media)
                        @php
                            $url = is_array($media) ? ($media['url'] ?? $media) : $media;
                            $type = is_array($media) ? ($media['type'] ?? 'image') : 'image';
                        @endphp
                        <div class="relative rounded-lg overflow-hidden border border-gray-200 bg-gray-50">
                            @if($type === 'video')
                                <video class="w-full h-32 object-cover bg-gray-100">
                                    <source src="{{ $url }}" />
                                </video>
                            @else
                                <img src="{{ $url }}" alt="Media" class="w-full h-32 object-cover" />
                            @endif
                            <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-40 flex items-center justify-center opacity-0 hover:opacity-100 transition-all">
                                <span class="text-white font-semibold text-sm bg-black bg-opacity-50 px-3 py-1 rounded-full">
                                    {{ $type === 'video' ? 'Video' : 'Image' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Add More Media -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Add More Media (Optional)</label>
            <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-green-300 hover:bg-green-50 transition-colors cursor-pointer">
                <input 
                    type="file" 
                    name="media[]" 
                    id="mediaInput"
                    accept="image/*,video/*"
                    multiple
                    class="hidden"
                    onchange="handleMediaSelect(this)"
                />
                <label for="mediaInput" class="cursor-pointer flex flex-col items-center gap-2">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <div>
                        <p class="font-semibold text-gray-900">Click to upload or drag & drop</p>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF, WebP, MP4, MOV (Max 10MB each)</p>
                    </div>
                </label>
            </div>
            @error('media')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Media Preview -->
        <div id="mediaPreview" class="grid grid-cols-2 sm:grid-cols-3 gap-2 hidden">
            <template id="mediaTemplate">
                <div class="relative rounded-lg overflow-hidden border border-gray-200 bg-gray-50">
                    <img class="w-full h-24 object-cover" />
                    <button type="button" class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors" onclick="removeMedia(this)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </template>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3 pt-4 border-t border-gray-100">
            <a href="{{ route('feed') }}" class="flex-1 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 font-semibold rounded-xl transition-colors text-center">
                Cancel
            </a>
            <button type="submit" class="flex-1 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl transition-colors flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Save Changes
            </button>
        </div>
    </form>
</div>

<script>
    function handleMediaSelect(input) {
        const preview = document.getElementById('mediaPreview');
        const template = document.getElementById('mediaTemplate');
        const container = preview.parentElement;

        // Clear existing previews
        const existingPreviews = preview.querySelectorAll(':scope > div:not(template)');
        existingPreviews.forEach(p => p.remove());

        if (input.files.length > 0) {
            preview.classList.remove('hidden');

            Array.from(input.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const clone = template.content.cloneNode(true);
                    clone.querySelector('img').src = e.target.result;
                    preview.insertBefore(clone, template);
                };
                reader.readAsDataURL(file);
            });
        }
    }

    function removeMedia(btn) {
        const preview = document.getElementById('mediaPreview');
        btn.closest('div').remove();

        if (preview.querySelectorAll(':scope > div:not(template)').length === 0) {
            preview.classList.add('hidden');
        }

        // Clear the input if all are removed
        const input = document.getElementById('mediaInput');
        if (preview.querySelectorAll(':scope > div:not(template)').length === 0) {
            input.value = '';
        }
    }
</script>
@endsection
