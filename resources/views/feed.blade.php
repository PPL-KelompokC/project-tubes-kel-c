@extends('layouts.app')

@section('title', 'Activity Feed - TerraVerde')

@section('content')
<div class="p-4 lg:p-6 max-w-2xl mx-auto space-y-4">
    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-2xl p-4 flex items-center gap-3">
            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Post Composer -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
        <form action="{{ route('feed.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
            @csrf
            <div class="flex items-start gap-3">
                <!-- User Avatar -->
                @if(auth()->user()->avatar)
                    <img
                        src="{{ auth()->user()->avatar }}"
                        alt="Your Avatar"
                        class="w-10 h-10 rounded-full object-cover ring-2 ring-green-200 flex-shrink-0"
                    />
                @else
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                @endif

                <div class="flex-1">
                    <!-- Caption Input -->
                    <textarea
                        name="caption"
                        placeholder="Share your eco action today... 🌱"
                        class="w-full text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 resize-none focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-green-400 @error('caption') border-red-400 ring-red-300 @enderror"
                        rows="2"
                        required
                    >{{ old('caption') }}</textarea>

                    @error('caption')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror

                    <!-- Media Preview -->
                    <div id="mediaPreview" class="mt-3 grid grid-cols-2 sm:grid-cols-3 gap-2 hidden">
                        <template id="mediaTemplate">
                            <div class="relative rounded-lg overflow-hidden border border-gray-200 bg-gray-50">
                                <img class="w-full h-24 object-cover" />
                                <button type="button" class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600" onclick="removeMedia(this)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between mt-3">
                        <div class="flex gap-2">
                            <!-- File Upload Button -->
                            <label class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors cursor-pointer" title="Add images or videos">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                <input 
                                    type="file" 
                                    name="media[]" 
                                    accept="image/*,video/*"
                                    multiple
                                    class="hidden"
                                    id="mediaInput"
                                    onchange="handleMediaSelect(this)"
                                />
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-xs font-semibold px-4 py-2 rounded-xl transition-all flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" x2="11" y1="2" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                            Post
                        </button>
                    </div>

                    @error('media')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </form>
    </div>

    <!-- Category Tabs -->
    <div class="flex gap-2 overflow-x-auto pb-2 -mx-4 px-4 lg:mx-0 lg:px-0 hide-scrollbar">
        <a href="#" class="px-4 py-2 bg-white border border-green-200 rounded-full text-sm font-semibold text-green-700 whitespace-nowrap shadow-sm">
            🌍 All
        </a>
        <a href="#" class="px-4 py-2 bg-white border border-gray-100 hover:border-gray-200 rounded-full text-sm font-medium text-gray-600 whitespace-nowrap shadow-sm transition-colors">
            ✅ Challenges
        </a>
        <a href="#" class="px-4 py-2 bg-white border border-gray-100 hover:border-gray-200 rounded-full text-sm font-medium text-gray-600 whitespace-nowrap shadow-sm transition-colors">
            🏅 Badges
        </a>
        <a href="#" class="px-4 py-2 bg-white border border-gray-100 hover:border-gray-200 rounded-full text-sm font-medium text-gray-600 whitespace-nowrap shadow-sm transition-colors">
            🔥 Streaks
        </a>
    </div>

    <!-- Feed Items -->
    @if($feeds->count() > 0)
        @foreach($feeds as $feed)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <!-- Header -->
                <div class="flex items-center justify-between p-4 pb-3">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        @if($feed->user->avatar)
                            <img src="{{ $feed->user->avatar }}" alt="{{ $feed->user->name }}" class="w-10 h-10 rounded-full object-cover ring-2 ring-gray-100 flex-shrink-0" />
                        @else
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                {{ substr($feed->user->name, 0, 1) }}
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-bold text-gray-900">{{ $feed->user->name }}</p>
                                @if($feed->user_id === auth()->id())
                                    <span class="text-[10px] bg-green-100 text-green-700 px-1.5 py-0.5 rounded-full font-medium">You</span>
                                @endif
                            </div>
                            <p class="text-[10px] text-gray-400 mt-0.5">{{ $feed->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Caption -->
                <div class="px-4 pb-3">
                    <p class="text-sm text-gray-800 leading-relaxed break-words">{{ $feed->caption }}</p>
                </div>

                <!-- Media Gallery -->
                @if($feed->media && is_array($feed->media) && count($feed->media) > 0)
                    <div class="px-4 pb-3">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach($feed->media as $media)
                                @php
                                    $url = is_array($media) ? ($media['url'] ?? $media) : $media;
                                    $type = is_array($media) ? ($media['type'] ?? 'image') : 'image';
                                @endphp
                                @if($type === 'video')
                                    <video 
                                        controls
                                        class="w-full h-52 object-cover rounded-xl bg-gray-100"
                                    >
                                        <source src="{{ $url }}" />
                                        Your browser does not support the video tag.
                                    </video>
                                @else
                                    <img src="{{ $url }}" alt="Feed media" class="w-full h-52 object-cover rounded-xl" />
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Engagement Stats -->
                <div class="px-4 py-3 bg-gradient-to-r from-green-50 to-emerald-50 border-t border-gray-100 flex items-center gap-4 text-xs">
                    <!-- Like Form -->
                    <form action="{{ route('feed.like.toggle', $feed->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center gap-1.5 hover:text-red-600 transition-colors cursor-pointer {{ $feed->likes->contains('user_id', auth()->id()) ? 'text-red-500' : 'text-gray-500' }}">
                            <svg class="w-4 h-4" fill="{{ $feed->likes->contains('user_id', auth()->id()) ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            <span class="font-medium">{{ $feed->likes_count }}</span>
                        </button>
                    </form>
                    <div class="flex items-center gap-1.5 cursor-pointer text-gray-500 hover:text-blue-600 transition-colors" onclick="document.getElementById('comments-{{ $feed->id }}').classList.toggle('hidden')">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        <span class="font-medium">{{ $feed->comments_count }} Comments</span>
                    </div>
                </div>

                <!-- Comments Section (Hidden by default) -->
                <div id="comments-{{ $feed->id }}" class="hidden bg-gray-50 border-t border-gray-100 px-4 py-3">
                    
                    <!-- Existing Comments -->
                    @if($feed->comments && $feed->comments->count() > 0)
                        <div class="space-y-4 mb-4">
                            @foreach($feed->comments as $comment)
                                <div class="flex gap-2.5">
                                    @if($comment->user->avatar)
                                        <img src="{{ $comment->user->avatar }}" class="w-7 h-7 rounded-full object-cover ring-1 ring-gray-200 flex-shrink-0" alt="Avatar">
                                    @else
                                        <div class="w-7 h-7 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold flex-shrink-0">
                                            {{ substr($comment->user->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <div class="bg-white border border-gray-100 p-2.5 rounded-xl rounded-tl-none shadow-sm">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="text-xs font-bold text-gray-900">{{ $comment->user->name }}</span>
                                                <span class="text-[9px] text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            @if($comment->content)
                                                <p class="text-xs text-gray-700 break-words mb-1">{!! $comment->formatted_content !!}</p>
                                            @endif
                                            @if($comment->image)
                                                <img src="{{ $comment->image }}" class="rounded-lg max-h-32 object-cover border border-gray-100" alt="Comment Image">
                                            @endif
                                        </div>
                                        
                                        <!-- Reply Button -->
                                        <div class="flex gap-4 mt-1 px-1">
                                            <button onclick="document.getElementById('reply-form-{{ $comment->id }}').classList.toggle('hidden')" class="text-[10px] font-semibold text-gray-500 hover:text-gray-700">Reply</button>
                                        </div>

                                        <!-- Hidden Reply Form -->
                                        <form id="reply-form-{{ $comment->id }}" action="{{ route('feed.comments.store', $feed->id) }}" method="POST" enctype="multipart/form-data" class="hidden flex gap-2 mt-2">
                                            @csrf
                                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                            <div class="flex-1 relative">
                                                <input 
                                                    type="text" 
                                                    name="content" 
                                                    placeholder="Reply to {{ $comment->user->name }}..." 
                                                    class="w-full text-xs bg-white border border-gray-200 rounded-full pl-3 pr-10 py-1.5 focus:outline-none focus:ring-1 focus:ring-green-400 focus:border-green-400"
                                                >
                                                <label class="absolute right-2 top-1/2 -translate-y-1/2 p-1 text-gray-400 hover:text-green-600 cursor-pointer rounded-full" title="Attach an image">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                                    <input type="file" name="image" accept="image/*" class="hidden" onchange="this.parentElement.style.color = '#16a34a';">
                                                </label>
                                            </div>
                                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white p-1.5 rounded-full flex-shrink-0 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" x2="11" y1="2" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                                            </button>
                                        </form>

                                        <!-- Replies -->
                                        @if($comment->replies && $comment->replies->count() > 0)
                                            <div class="mt-3 space-y-3">
                                                @foreach($comment->replies as $reply)
                                                    <div class="flex gap-2">
                                                        @if($reply->user->avatar)
                                                            <img src="{{ $reply->user->avatar }}" class="w-6 h-6 rounded-full object-cover ring-1 ring-gray-200 flex-shrink-0" alt="Avatar">
                                                        @else
                                                            <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-[10px] font-bold flex-shrink-0">
                                                                {{ substr($reply->user->name, 0, 1) }}
                                                            </div>
                                                        @endif
                                                        <div class="flex-1 min-w-0 bg-white border border-gray-100 p-2 rounded-xl rounded-tl-none shadow-sm">
                                                            <div class="flex items-center justify-between mb-0.5">
                                                                <span class="text-[11px] font-bold text-gray-900">{{ $reply->user->name }}</span>
                                                                <span class="text-[9px] text-gray-400">{{ $reply->created_at->diffForHumans() }}</span>
                                                            </div>
                                                            @if($reply->content)
                                                                <p class="text-[11px] text-gray-700 break-words mb-1">{!! $reply->formatted_content !!}</p>
                                                            @endif
                                                            @if($reply->image)
                                                                <img src="{{ $reply->image }}" class="rounded-lg max-h-24 object-cover border border-gray-100" alt="Reply Image">
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Comment Form -->
                    <form action="{{ route('feed.comments.store', $feed->id) }}" method="POST" enctype="multipart/form-data" class="flex gap-2 mt-2 pt-2 border-t border-gray-200">
                        @csrf
                        <div class="flex-1 relative">
                            <input 
                                type="text" 
                                name="content" 
                                placeholder="Write a comment..." 
                                class="w-full text-xs bg-white border border-gray-200 rounded-full pl-3 pr-10 py-2 focus:outline-none focus:ring-1 focus:ring-green-400 focus:border-green-400"
                            >
                            <!-- Image Upload Button for Comment -->
                            <label class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 text-gray-400 hover:text-green-600 cursor-pointer rounded-full hover:bg-gray-100 transition-colors" title="Attach an image">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                <input type="file" name="image" accept="image/*" class="hidden" onchange="this.parentElement.style.color = '#16a34a'; this.parentElement.title = 'Image selected: ' + this.files[0].name;">
                            </label>
                        </div>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white p-2 rounded-full flex-shrink-0 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" x2="11" y1="2" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                        </button>
                    </form>
                    
                    @error('content') <p class="text-[10px] text-red-500 mt-1 pl-2">{{ $message }}</p> @enderror
                    @error('image') <p class="text-[10px] text-red-500 mt-1 pl-2">{{ $message }}</p> @enderror
                </div>
            </div>
        @endforeach

        <!-- Pagination -->
        @if($feeds->hasPages())
            <div class="mt-6">
                {{ $feeds->links('pagination::tailwind') }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 text-center">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m0 0h6m0-6h-6m0-6H6"/></svg>
            <h3 class="text-lg font-semibold text-gray-600 mb-1">No posts yet</h3>
            <p class="text-sm text-gray-500">Be the first to share your eco action! 🌱</p>
        </div>
    @endif
</div>

<script>
// Handle media selection with preview
function handleMediaSelect(input) {
    const files = input.files;
    const preview = document.getElementById('mediaPreview');
    const template = document.getElementById('mediaTemplate');
    
    // Clear previous previews
    preview.innerHTML = '';
    
    if (files.length > 0) {
        preview.classList.remove('hidden');
        
        Array.from(files).forEach((file, index) => {
            if (index < 5) { // Limit to 5 files
                const reader = new FileReader();
                reader.onload = (e) => {
                    const clone = template.content.cloneNode(true);
                    clone.querySelector('img').src = e.target.result;
                    preview.appendChild(clone);
                };
                reader.readAsDataURL(file);
            }
        });
    }
}

function removeMedia(btn) {
    btn.closest('div').remove();
    const preview = document.getElementById('mediaPreview');
    if (preview.children.length === 0) {
        preview.classList.add('hidden');
    }
}
</script>

@endsection
