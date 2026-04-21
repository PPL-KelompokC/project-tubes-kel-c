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
                    <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 text-center">
                        <p class="text-xs text-slate-600 uppercase font-semibold">Likes</p>
                        <p class="text-3xl font-bold text-slate-900 mt-1">{{ $feed->likes_count }}</p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 text-center">
                        <p class="text-xs text-slate-600 uppercase font-semibold">Comments</p>
                        <p class="text-3xl font-bold text-slate-900 mt-1">{{ $feed->comments_count }}</p>
                    </div>
                </div>
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
                <form action="{{ route('admin.feeds.show', $feed->id) }}" method="POST">
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
@endsection
