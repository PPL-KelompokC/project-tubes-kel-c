@extends('layouts.app')

@section('title', 'Post Detail - TerraVerde')

@section('content')
<div class="p-4 lg:p-6 max-w-2xl mx-auto">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('feed') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Feed
        </a>
    </div>

    <!-- Post Card -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 pb-3">
            <div class="flex items-center gap-3 flex-1 min-w-0">
                @if($feed->user->avatar)
                    <img src="{{ $feed->user->avatar }}" alt="{{ $feed->user->name }}" class="w-12 h-12 rounded-full object-cover ring-2 ring-gray-100 flex-shrink-0" />
                @else
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white font-bold flex-shrink-0">
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
                    <p class="text-xs text-gray-400 mt-0.5">{{ $feed->created_at->format('M d, Y \a\t g:i A') }}</p>
                </div>
            </div>

            <!-- Edit/Delete Actions (only for post owner) -->
            @if($feed->user_id === auth()->id())
                <div class="flex gap-2">
                    <a href="{{ route('feed.edit', $feed) }}" class="p-2 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Edit post">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </a>
                    <button 
                        onclick="openDeleteModal('{{ $feed->id }}', '{{ addslashes($feed->user->name) }}')"
                        class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                        title="Delete post"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            @endif
        </div>

        <!-- Caption -->
        <div class="px-4 pb-3">
            <p class="text-base text-gray-800 leading-relaxed break-words">{{ $feed->caption }}</p>
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
                                class="w-full h-80 object-cover rounded-xl bg-gray-100"
                            >
                                <source src="{{ $url }}" />
                                Your browser does not support the video tag.
                            </video>
                        @else
                            <img src="{{ $url }}" alt="Feed media" class="w-full h-80 object-cover rounded-xl cursor-pointer hover:opacity-90 transition-opacity" onclick="openImageModal('{{ $url }}')" />
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Engagement Stats -->
        <div class="px-4 py-3 bg-gradient-to-r from-green-50 to-emerald-50 border-t border-gray-100 flex items-center gap-4 text-sm">
            <!-- Like Form -->
            <form action="{{ route('feed.like.toggle', $feed->id) }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center gap-1.5 hover:text-red-600 transition-colors cursor-pointer {{ $feed->likes->contains('user_id', auth()->id()) ? 'text-red-500' : 'text-gray-500' }}">
                    <svg class="w-5 h-5" fill="{{ $feed->likes->contains('user_id', auth()->id()) ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    <span class="font-bold">{{ $feed->likes_count }} Likes</span>
                </button>
            </form>
            <div class="flex items-center gap-1.5 text-gray-500">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                <span class="font-bold">{{ $feed->comments_count }} Comments</span>
            </div>
        </div>
    </div>

    <!-- Comments Section -->
    <div class="mt-6 space-y-4">
        <h2 class="text-lg font-bold text-gray-900 px-4">Comments ({{ $feed->comments_count }})</h2>

        <!-- Comment Form -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
            <form action="{{ route('feed.comments.store', $feed->id) }}" method="POST" enctype="multipart/form-data" class="flex gap-3">
                @csrf
                @if(auth()->user()->avatar)
                    <img src="{{ auth()->user()->avatar }}" alt="Your Avatar" class="w-10 h-10 rounded-full object-cover ring-1 ring-gray-200 flex-shrink-0" />
                @else
                    <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold flex-shrink-0">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                @endif
                <div class="flex-1">
                    <div class="relative flex gap-2">
                        <input 
                            type="text" 
                            name="content" 
                            placeholder="Write a comment..." 
                            class="flex-1 text-sm bg-gray-50 border border-gray-200 rounded-full pl-4 pr-10 py-2.5 focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-green-400"
                        >
                        <label class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-gray-400 hover:text-green-600 cursor-pointer rounded-full hover:bg-gray-100 transition-colors" title="Attach an image">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                            <input type="file" name="image" accept="image/*" class="hidden" onchange="this.parentElement.style.color = '#16a34a'; this.parentElement.title = 'Image selected: ' + this.files[0].name;">
                        </label>
                    </div>
                    @error('content') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white p-2.5 rounded-full flex-shrink-0 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" x2="11" y1="2" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                </button>
            </form>
        </div>

        <!-- Comments List -->
        @if($feed->comments && $feed->comments->count() > 0)
            <div class="space-y-4">
                @foreach($feed->comments as $comment)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex gap-3">
                        @if($comment->user->avatar)
                            <img src="{{ $comment->user->avatar }}" class="w-10 h-10 rounded-full object-cover ring-1 ring-gray-200 flex-shrink-0" alt="Avatar">
                        @else
                            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold flex-shrink-0">
                                {{ substr($comment->user->name, 0, 1) }}
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <div>
                                    <span class="text-sm font-bold text-gray-900">{{ $comment->user->name }}</span>
                                    <span class="text-xs text-gray-400 ml-2">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                @if($comment->user_id === auth()->id())
                                    <button 
                                        onclick="openDeleteCommentModal('{{ $feed->id }}', '{{ $comment->id }}')"
                                        class="p-1 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors"
                                        title="Delete comment"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                            @if($comment->content)
                                <p class="text-sm text-gray-700 break-words mb-2">{!! $comment->formatted_content !!}</p>
                            @endif
                            @if($comment->image)
                                <img src="{{ $comment->image }}" class="rounded-lg max-h-48 object-cover border border-gray-100 cursor-pointer hover:opacity-90 transition-opacity mb-2" alt="Comment Image" onclick="openImageModal('{{ $comment->image }}')">
                            @endif
                        </div>
                    </div>

                    <!-- Replies -->
                    @if($comment->replies && $comment->replies->count() > 0)
                        <div class="ml-12 space-y-3">
                            @foreach($comment->replies as $reply)
                                <div class="bg-gray-50 rounded-lg border border-gray-100 p-3 flex gap-2.5">
                                    @if($reply->user->avatar)
                                        <img src="{{ $reply->user->avatar }}" class="w-8 h-8 rounded-full object-cover ring-1 ring-gray-200 flex-shrink-0" alt="Avatar">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold flex-shrink-0">
                                            {{ substr($reply->user->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-2 mb-0.5">
                                            <div>
                                                <span class="text-xs font-bold text-gray-900">{{ $reply->user->name }}</span>
                                                <span class="text-[10px] text-gray-400 ml-2">{{ $reply->created_at->diffForHumans() }}</span>
                                            </div>
                                            @if($reply->user_id === auth()->id())
                                                <button 
                                                    onclick="openDeleteCommentModal('{{ $feed->id }}', '{{ $reply->id }}')"
                                                    class="p-0.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors"
                                                    title="Delete reply"
                                                >
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                        @if($reply->content)
                                            <p class="text-xs text-gray-700 break-words mb-1">{!! $reply->formatted_content !!}</p>
                                        @endif
                                        @if($reply->image)
                                            <img src="{{ $reply->image }}" class="rounded max-h-32 object-cover border border-gray-100 cursor-pointer hover:opacity-90 transition-opacity" alt="Reply Image" onclick="openImageModal('{{ $reply->image }}')">
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <div class="bg-gray-50 rounded-2xl border border-gray-100 p-8 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <p class="text-gray-500 font-medium">No comments yet. Be the first to comment!</p>
            </div>
        @endif
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4" onclick="if(event.target === this) closeImageModal();">
    <div class="relative max-w-4xl w-full">
        <button onclick="closeImageModal()" class="absolute -top-10 right-0 text-white hover:text-gray-300">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <img id="modalImage" src="" alt="Full size image" class="w-full rounded-lg">
    </div>
</div>

<!-- Delete Comment Modal -->
<div id="deleteCommentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-sm w-full">
        <div class="bg-red-50 px-6 py-4 border-b border-red-200 rounded-t-2xl flex items-center gap-3">
            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 0v2m0-2v-2m0-4v-2M6.228 6.228l1.414 1.414m9.9-1.414l1.414-1.414m0 9.9l1.414 1.414m-1.414 9.9l-1.414-1.414m9.9-1.414l1.414 1.414m-1.414 9.9l-1.414-1.414M6.228 17.772l1.414 1.414m9.9-1.414l1.414 1.414m-9.9-15.85a9 9 0 1018 0 9 9 0 00-18 0z"></path>
                </svg>
            </div>
            <h2 class="text-lg font-bold text-gray-900">Delete Comment?</h2>
        </div>
        <div class="px-6 py-4">
            <p class="text-gray-700 text-sm mb-3">Are you sure you want to delete this comment? This action cannot be undone.</p>
        </div>
        <div class="bg-gray-50 px-6 py-4 rounded-b-2xl border-t border-gray-200 flex gap-3">
            <button 
                onclick="closeDeleteCommentModal()"
                class="flex-1 px-4 py-2 bg-white border border-gray-300 text-gray-900 font-semibold rounded-lg hover:bg-gray-100 transition-colors"
            >
                Cancel
            </button>
            <button 
                onclick="confirmDeleteComment()"
                class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors flex items-center justify-center gap-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Delete
            </button>
        </div>
    </div>
</div>

<!-- Delete Post Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-sm w-full">
        <div class="bg-red-50 px-6 py-4 border-b border-red-200 rounded-t-2xl flex items-center gap-3">
            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 0v2m0-2v-2m0-4v-2M6.228 6.228l1.414 1.414m9.9-1.414l1.414-1.414m0 9.9l1.414 1.414m-1.414 9.9l-1.414-1.414m9.9-1.414l1.414 1.414m-1.414 9.9l-1.414-1.414M6.228 17.772l1.414 1.414m9.9-1.414l1.414 1.414m-9.9-15.85a9 9 0 1018 0 9 9 0 00-18 0z"></path>
                </svg>
            </div>
            <h2 class="text-lg font-bold text-gray-900">Delete Post?</h2>
        </div>
        <div class="px-6 py-4">
            <p class="text-gray-700 text-sm">Are you sure you want to delete this post? This action cannot be undone.</p>
        </div>
        <div class="bg-gray-50 px-6 py-4 rounded-b-2xl border-t border-gray-200 flex gap-3">
            <button 
                onclick="closeDeleteModal()"
                class="flex-1 px-4 py-2 bg-white border border-gray-300 text-gray-900 font-semibold rounded-lg hover:bg-gray-100 transition-colors"
            >
                Cancel
            </button>
            <button 
                onclick="confirmDelete()"
                class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors flex items-center justify-center gap-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Delete Post
            </button>
        </div>
    </div>
</div>

<!-- Hidden Delete Forms -->
<form id="deleteForm" action="" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<form id="deleteCommentForm" action="" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
    let deletePostData = { feedId: null };
    let deleteCommentData = { feedId: null, commentId: null };

    function openImageModal(src) {
        document.getElementById('imageModal').classList.remove('hidden');
        document.getElementById('modalImage').src = src;
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function openDeleteModal(feedId, userName) {
        deletePostData = { feedId };
        document.getElementById('deleteModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function confirmDelete() {
        if (deletePostData.feedId) {
            const form = document.getElementById('deleteForm');
            form.action = '/feed/' + deletePostData.feedId;
            form.submit();
        }
    }

    function openDeleteCommentModal(feedId, commentId) {
        deleteCommentData = { feedId, commentId };
        document.getElementById('deleteCommentModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteCommentModal() {
        document.getElementById('deleteCommentModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function confirmDeleteComment() {
        if (deleteCommentData.feedId && deleteCommentData.commentId) {
            const form = document.getElementById('deleteCommentForm');
            form.action = '/feed/' + deleteCommentData.feedId + '/comments/' + deleteCommentData.commentId;
            form.submit();
        }
    }

    // Close modals on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
            closeDeleteModal();
            closeDeleteCommentModal();
        }
    });

    // Close delete modals when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
    });

    document.getElementById('deleteCommentModal').addEventListener('click', function(e) {
        if (e.target === this) closeDeleteCommentModal();
    });
</script>
@endsection
