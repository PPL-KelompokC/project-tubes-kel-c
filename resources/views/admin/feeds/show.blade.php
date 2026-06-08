@extends('admin.layouts.app')

@section('title', 'Feed Details')

@section('content')
<div class="p-6 space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.feeds.index') }}" class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Feeds
        </a>
    </div>

    <!-- Header -->
    <div class="bg-white rounded-2xl border border-slate-200 card-shadow p-6">
        <div class="flex items-start justify-between mb-6">
            <div class="flex items-start gap-4">
                <img 
                    src="{{ $feed->user->avatar ?? 'https://via.placeholder.com/100' }}" 
                    alt="{{ $feed->user->name }}"
                    class="w-16 h-16 rounded-full object-cover"
                />
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">{{ $feed->user->name }}</h1>
                    <p class="text-sm text-slate-500 mt-2">{{ $feed->user->email }}</p>
                </div>
            </div>
            <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-medium
                {{ $feed->status === 'active' ? 'bg-green-50 text-green-700' : 'bg-orange-50 text-orange-700' }}">
                <span class="w-2 h-2 rounded-full {{ $feed->status === 'active' ? 'bg-green-600' : 'bg-orange-600' }}"></span>
                {{ ucfirst($feed->status) }}
            </span>
        </div>

        <!-- User Info Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="p-4 bg-slate-50 rounded-lg border border-slate-200">
                <p class="text-xs text-slate-600 uppercase font-semibold">Posts</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $feed->user->feeds()->count() }}</p>
            </div>
            <div class="p-4 bg-slate-50 rounded-lg border border-slate-200">
                <p class="text-xs text-slate-600 uppercase font-semibold">Member Since</p>
                <p class="text-sm font-semibold text-slate-900 mt-1">{{ $feed->user->created_at->format('M d, Y') }}</p>
            </div>
            <div class="p-4 bg-slate-50 rounded-lg border border-slate-200">
                <p class="text-xs text-slate-600 uppercase font-semibold">Points</p>
                <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $feed->user->points }}</p>
            </div>
            <div class="p-4 bg-slate-50 rounded-lg border border-slate-200">
                <p class="text-xs text-slate-600 uppercase font-semibold">Role</p>
                <p class="text-sm font-semibold text-slate-900 mt-1">{{ ucfirst($feed->user->role) }}</p>
            </div>
        </div>
    </div>

    <!-- Feed Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Post Details -->
            <div class="bg-white rounded-2xl border border-slate-200 card-shadow p-6 space-y-6">
                <div>
                    <h3 class="text-sm font-semibold text-slate-600 uppercase mb-2">Caption</h3>
                    <p class="text-slate-900 leading-relaxed">{{ $feed->caption }}</p>
                </div>

                <!-- Media Gallery -->
                @if($feed->media && count($feed->media) > 0)
                <div>
                    <h3 class="text-sm font-semibold text-slate-600 uppercase mb-3">Media Attached</h3>
                    <div class="grid grid-cols-2 gap-4">
                        @foreach($feed->media as $media)
                        <div class="rounded-xl overflow-hidden border border-slate-200 bg-slate-50">
                            <img 
                                src="{{ is_array($media) ? $media['url'] : $media }}" 
                                alt="Post media"
                                class="w-full h-48 object-cover"
                            />
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Metadata -->
                <div class="pt-4 border-t border-slate-200">
                    <h3 class="text-sm font-semibold text-slate-600 uppercase mb-3">Metadata</h3>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Feed Type:</span>
                            <span class="text-sm font-medium text-slate-900">{{ ucfirst($feed->feed_type) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Created:</span>
                            <span class="text-sm font-medium text-slate-900">{{ $feed->created_at->format('d M Y H:i') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Last Updated:</span>
                            <span class="text-sm font-medium text-slate-900">{{ $feed->updated_at->format('d M Y H:i') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Time Ago:</span>
                            <span class="text-sm font-medium text-slate-900">{{ $feed->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Engagement Stats -->
            <div class="bg-white rounded-2xl border border-slate-200 card-shadow p-6">
                <h2 class="text-lg font-bold text-slate-900 mb-4">Engagement</h2>
                <div class="grid grid-cols-2 gap-4">
                    <form action="{{ route('feed.like.toggle', $feed->id) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="w-full p-4 bg-slate-50 hover:bg-slate-100 transition-colors rounded-lg border border-slate-200 text-center block focus:outline-none">
                            <p class="text-xs text-slate-600 uppercase font-semibold flex items-center justify-center gap-1">
                                <svg class="w-4 h-4 {{ $feed->likes->contains('user_id', auth()->id()) ? 'text-rose-500 fill-current' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                Likes
                            </p>
                            <p class="text-3xl font-bold text-slate-900 mt-1">{{ $feed->likes_count }}</p>
                        </button>
                    </form>
                    <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 text-center">
                        <p class="text-xs text-slate-600 uppercase font-semibold">Comments</p>
                        <p class="text-3xl font-bold text-slate-900 mt-1">{{ $feed->comments_count }}</p>
                    </div>
                </div>

                <!-- Likers List -->
                @if($feed->likes && $feed->likes->count() > 0)
                <div class="mt-6 pt-6 border-t border-slate-200">
                    <h3 class="text-sm font-semibold text-slate-600 uppercase mb-3">Users who liked this post</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($feed->likes as $like)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 text-red-700 text-xs font-medium rounded-full border border-red-100">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>
                                {{ $like->user->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Comments & Replies Tree -->
            <div class="bg-white rounded-2xl border border-slate-200 card-shadow p-6">
                <h2 class="text-lg font-bold text-slate-900 mb-6">Comments & Replies</h2>

                <!-- Comment Form -->
                <div class="mb-6 pb-6 border-b border-slate-200">
                    <form action="{{ route('admin.feeds.comments.store', $feed->id) }}" method="POST" class="flex gap-3">
                        @csrf
                        @if(auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar }}" alt="Your Avatar" class="w-10 h-10 rounded-full object-cover ring-2 ring-slate-200 flex-shrink-0" />
                        @else
                            <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm font-bold flex-shrink-0">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        @endif
                        <div class="flex-1">
                            <div class="relative flex gap-2">
                                <input 
                                    type="text" 
                                    name="content" 
                                    placeholder="Add your comment..." 
                                    class="flex-1 text-sm bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:border-emerald-400"
                                >
                            </div>
                            @error('content') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white p-2.5 rounded-lg flex-shrink-0 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" x2="11" y1="2" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                        </button>
                    </form>
                </div>

                <!-- Comments List -->
                @if($feed->comments && $feed->comments->count() > 0)
                    <div class="space-y-6">
                        @foreach($feed->comments as $comment)
                            <!-- Parent Comment -->
                            <div class="relative">
                                <div class="flex gap-4">
                                    <img src="{{ $comment->user->avatar ?? 'https://via.placeholder.com/40' }}" alt="avatar" class="w-10 h-10 rounded-full object-cover ring-2 ring-slate-100 flex-shrink-0">
                                    <div class="flex-1 bg-slate-50 border border-slate-200 rounded-xl rounded-tl-none p-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <div>
                                                <span class="font-bold text-slate-900">{{ $comment->user->name }}</span>
                                                @if($comment->user->role === 'admin')
                                                    <span class="ml-2 text-xs font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">Admin</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs text-slate-500">{{ $comment->created_at->format('d M, H:i') }}</span>
                                                @if($comment->user_id === auth()->id())
                                                    <button 
                                                        onclick="if(confirm('Delete this comment?')) { document.getElementById('delete-comment-{{ $comment->id }}').submit(); }"
                                                        class="p-1 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors"
                                                        title="Delete comment"
                                                    >
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                    <form id="delete-comment-{{ $comment->id }}" action="{{ route('admin.feeds.comments.destroy', [$feed->id, $comment->id]) }}" method="POST" style="display:none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                        @if($comment->content)
                                            <p class="text-sm text-slate-700 break-words mb-2">{!! $comment->formatted_content !!}</p>
                                        @endif
                                        @if($comment->image)
                                            <img src="{{ $comment->image }}" class="rounded-lg max-h-40 object-cover border border-slate-200" alt="Comment Image">
                                        @endif
                                    </div>
                                </div>

                                <!-- Reply Button & Form -->
                                <div class="flex gap-4 mt-1 px-1 ml-14 mb-2">
                                    <button onclick="document.getElementById('reply-form-{{ $comment->id }}').classList.toggle('hidden')" class="text-[10px] font-semibold text-slate-500 hover:text-slate-700">Reply</button>
                                </div>

                                <form id="reply-form-{{ $comment->id }}" action="{{ route('admin.feeds.comments.store', $feed->id) }}" method="POST" class="hidden flex gap-2 mt-2 ml-14 mb-4">
                                    @csrf
                                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                    <div class="flex-1 relative">
                                        <input 
                                            type="text" 
                                            id="reply-input-{{ $comment->id }}"
                                            name="content" 
                                            placeholder="Reply to {{ $comment->user->name }}..." 
                                            class="w-full text-xs bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-1 focus:ring-emerald-400 focus:border-emerald-400"
                                        >
                                    </div>
                                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white p-1.5 rounded-lg flex-shrink-0 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" x2="11" y1="2" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                                    </button>
                                </form>

                                <!-- Replies -->
                                @if($comment->replies && $comment->replies->count() > 0)
                                    <div class="mt-4 ml-12 space-y-4">
                                        @foreach($comment->replies as $reply)
                                            <div class="flex gap-3 relative before:absolute before:-left-8 before:top-4 before:w-6 before:h-px before:bg-slate-300">
                                                <div class="absolute -left-8 -top-8 w-px h-12 bg-slate-300"></div>
                                                <img src="{{ $reply->user->avatar ?? 'https://via.placeholder.com/32' }}" alt="avatar" class="w-8 h-8 rounded-full object-cover ring-2 ring-slate-100 flex-shrink-0 z-10">
                                                <div class="flex-1 bg-white border border-slate-200 rounded-xl rounded-tl-none p-3">
                                                    <div class="flex items-center justify-between mb-1">
                                                        <div>
                                                            <span class="text-sm font-bold text-slate-900">{{ $reply->user->name }}</span>
                                                            @if($reply->user->role === 'admin')
                                                                <span class="ml-1 text-[10px] font-semibold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded">Admin</span>
                                                            @endif
                                                        </div>
                                                        <div class="flex items-center gap-1">
                                                            <span class="text-[10px] text-slate-500">{{ $reply->created_at->format('d M, H:i') }}</span>
                                                            @if($reply->user_id === auth()->id())
                                                                <button 
                                                                    onclick="if(confirm('Delete this reply?')) { document.getElementById('delete-reply-{{ $reply->id }}').submit(); }"
                                                                    class="p-0.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors"
                                                                    title="Delete reply"
                                                                >
                                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                    </svg>
                                                                </button>
                                                                <form id="delete-reply-{{ $reply->id }}" action="{{ route('admin.feeds.comments.destroy', [$feed->id, $reply->id]) }}" method="POST" style="display:none;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @if($reply->content)
                                                        <p class="text-xs text-slate-700 break-words">{!! $reply->formatted_content !!}</p>
                                                    @endif
                                                    @if($reply->image)
                                                        <img src="{{ $reply->image }}" class="rounded-lg max-h-24 object-cover border border-slate-200 mt-2" alt="Reply Image">
                                                    @endif
                                                    <!-- Reply to Reply Button -->
                                                    <div class="flex justify-end mt-1 px-1">
                                                        <button type="button" onclick="showReplyForm('{{ $comment->id }}', '{{ str_replace(' ', '', $reply->user->name) }}')" class="text-[9px] font-semibold text-slate-500 hover:text-slate-700">Reply</button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-slate-500">No comments have been posted yet.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-4">
            <!-- Action Buttons -->
            <div class="bg-white rounded-2xl border border-slate-200 card-shadow p-6 space-y-3">
                <h3 class="font-semibold text-slate-900 mb-4">Actions</h3>

                <!-- View Post -->
                <a 
                    href="#"
                    class="w-full px-4 py-3 bg-blue-50 text-blue-700 rounded-lg font-medium hover:bg-blue-100 transition-200 flex items-center justify-center gap-2"
                    title="View public post"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    View Public Post
                </a>

                <!-- Toggle Status -->
                @if($feed->status === 'active')
                <form action="{{ route('admin.feeds.hide', $feed->id) }}" method="POST">
                    @csrf
                    <button 
                        type="submit"
                        class="w-full px-4 py-3 bg-orange-50 text-orange-700 rounded-lg font-medium hover:bg-orange-100 transition-200 flex items-center justify-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-4.803m5.596-3.856a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0m7.111 0a10.05 10.05 0 01-15.937 4.803 10.05 10.05 0 0115.937-4.803z M9.73 12a2.25 2.25 0 100 2.25M3 3l18 18"/></svg>
                        Hide Post
                    </button>
                </form>
                @else
                <form action="{{ route('admin.feeds.unhide', $feed->id) }}" method="POST">
                    @csrf
                    <button 
                        type="submit"
                        class="w-full px-4 py-3 bg-green-50 text-green-700 rounded-lg font-medium hover:bg-green-100 transition-200 flex items-center justify-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Show Post
                    </button>
                </form>
                @endif

                <!-- Delete -->
                <form action="{{ route('admin.feeds.destroy', $feed->id) }}" method="POST" onsubmit="return confirm('Permanently delete this post? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button 
                        type="submit"
                        class="w-full px-4 py-3 bg-red-50 text-red-700 rounded-lg font-medium hover:bg-red-100 transition-200 flex items-center justify-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Delete Post
                    </button>
                </form>
            </div>

            <!-- Status Info -->
            <div class="bg-white rounded-2xl border border-slate-200 card-shadow p-6">
                <h3 class="font-semibold text-slate-900 mb-3">Status Information</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full {{ $feed->status === 'active' ? 'bg-green-600' : 'bg-orange-600' }}"></span>
                        <span class="text-slate-600">
                            @if($feed->status === 'active')
                                Post is <span class="font-semibold text-green-700">visible</span> to all users
                            @else
                                Post is <span class="font-semibold text-orange-700">hidden</span> from public view
                            @endif
                        </span>
                    </div>
                    <div class="pt-2 mt-2 border-t border-slate-200 text-xs text-slate-500">
                        <p>Use the action buttons to manage this post's visibility or remove it completely from the system.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showReplyForm(commentId, userName) {
    const form = document.getElementById('reply-form-' + commentId);
    const input = document.getElementById('reply-input-' + commentId);
    if (form && input) {
        form.classList.remove('hidden');
        input.value = '@' + userName + ' ';
        input.focus();
    }
}
</script>
@endsection
