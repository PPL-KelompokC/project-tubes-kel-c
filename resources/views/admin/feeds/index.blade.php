@extends('admin.layouts.app')

@section('title', 'Activity Feed Management')
@section('page_title', 'Activity Feed Management')
@section('page_subtitle', 'Monitor and moderate all user activity feeds across the platform.')

@section('content')
<div class="p-6 space-y-6">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Activity Feed Management</h1>
            <p class="text-sm text-slate-600 mt-1">Review, hide, or delete user activity feeds</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 card-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">Total</span>
            </div>
            <p class="text-sm font-medium text-slate-600">Total Feeds</p>
            <h3 class="text-3xl font-bold text-slate-900 mt-1">{{ $stats['total'] }}</h3>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 card-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-green-50 text-green-600 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full">Active</span>
            </div>
            <p class="text-sm font-medium text-slate-600">Active Posts</p>
            <h3 class="text-3xl font-bold text-slate-900 mt-1">{{ $stats['active'] }}</h3>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 card-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.658 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                </div>
                <span class="text-xs font-semibold text-orange-600 bg-orange-50 px-2 py-1 rounded-full">Hidden</span>
            </div>
            <p class="text-sm font-medium text-slate-600">Hidden Posts</p>
            <h3 class="text-3xl font-bold text-slate-900 mt-1">{{ $stats['hidden'] }}</h3>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white rounded-2xl border border-slate-200 card-shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Search -->
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <form method="GET" action="{{ route('admin.feeds.search') }}" class="flex gap-2">
                    <input 
                        type="text" 
                        name="q" 
                        placeholder="Search by user name or caption..." 
                        value="{{ $query ?? '' }}"
                        class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-200"
                    />
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg font-medium hover:bg-emerald-700 transition-200">
                        Search
                    </button>
                </form>
            </div>

            <!-- Status Filter -->
            <div class="flex gap-2">
                <a href="{{ route('admin.feeds.index') }}" class="px-4 py-2 bg-slate-100 text-slate-900 rounded-lg font-medium hover:bg-slate-200 transition-200 {{ !isset($status) || $status === 'all' ? 'bg-emerald-100 text-emerald-900 border border-emerald-300' : '' }}">
                    All Posts
                </a>
                <a href="{{ route('admin.feeds.filter', ['status' => 'active']) }}" class="px-4 py-2 bg-slate-100 text-slate-900 rounded-lg font-medium hover:bg-slate-200 transition-200 {{ isset($status) && $status === 'active' ? 'bg-emerald-100 text-emerald-900 border border-emerald-300' : '' }}">
                    Active
                </a>
                <a href="{{ route('admin.feeds.filter', ['status' => 'hidden']) }}" class="px-4 py-2 bg-slate-100 text-slate-900 rounded-lg font-medium hover:bg-slate-200 transition-200 {{ isset($status) && $status === 'hidden' ? 'bg-emerald-100 text-emerald-900 border border-emerald-300' : '' }}">
                    Hidden
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if($message = session('success'))
    <div class="bg-green-50 border border-green-200 rounded-2xl p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div>
            <p class="font-medium text-green-900">{{ $message }}</p>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-2xl p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2M9 5H5a2 2 0 00-2 2v12a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2h-4m0 0V3a2 2 0 00-2-2h-2a2 2 0 00-2 2v2z"/></svg>
        <div>
            <p class="font-medium text-red-900">Error occurred while processing your request</p>
        </div>
    </div>
    @endif

    <!-- Feeds Table -->
    <div class="bg-white rounded-2xl border border-slate-200 card-shadow overflow-hidden">
        @if($feeds->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">User</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Caption</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Media</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Engagement</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Posted</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($feeds as $feed)
                        <tr class="hover:bg-slate-50 transition-200">
                            <!-- User -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img 
                                        src="{{ $feed->user->avatar ?? 'https://via.placeholder.com/100' }}" 
                                        alt="{{ $feed->user->name }}"
                                        class="w-10 h-10 rounded-full object-cover"
                                    />
                                    <div>
                                        <p class="font-medium text-slate-900">{{ $feed->user->name }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Caption -->
                            <td class="px-6 py-4">
                                <p class="text-sm text-slate-600 line-clamp-2">{{ Str::limit($feed->caption, 100) }}</p>
                            </td>

                            <!-- Media -->
                            <td class="px-6 py-4">
                                @if($feed->media && count($feed->media) > 0)
                                    <div class="flex items-center gap-2">
                                        @foreach($feed->media as $index => $media)
                                            @if($index < 2)
                                                <div class="relative w-10 h-10 rounded-lg overflow-hidden border border-slate-200">
                                                    <img 
                                                        src="{{ $media['url'] ?? $media }}" 
                                                        alt="Media"
                                                        class="w-full h-full object-cover"
                                                    />
                                                </div>
                                            @endif
                                        @endforeach
                                        @if(count($feed->media) > 2)
                                            <span class="text-xs font-medium text-slate-500">+{{ count($feed->media) - 2 }} more</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400">No media</span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4">
                                @if($feed->status === 'active')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-700 rounded-full text-xs font-medium">
                                        <span class="w-2 h-2 bg-green-600 rounded-full"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-orange-50 text-orange-700 rounded-full text-xs font-medium">
                                        <span class="w-2 h-2 bg-orange-600 rounded-full"></span>
                                        Hidden
                                    </span>
                                @endif
                            </td>

                            <!-- Engagement -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3 text-sm">
                                    <div class="flex items-center gap-1 text-slate-600">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                                        {{ $feed->likes_count }}
                                    </div>
                                    <div class="flex items-center gap-1 text-slate-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                        {{ $feed->comments_count }}
                                    </div>
                                </div>
                            </td>

                            <!-- Posted -->
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    <p class="font-medium text-slate-900">{{ $feed->created_at->format('M d, Y') }}</p>
                                    <p class="text-xs text-slate-500">{{ $feed->created_at->diffForHumans() }}</p>
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <!-- View Detail -->
                                    <a 
                                        href="{{ route('admin.feeds.show', $feed->id) }}"
                                        class="p-2 text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-200"
                                        title="View Details"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>

                                    <!-- Hide/Show Toggle -->
                                    @if($feed->status === 'active')
                                        <form action="{{ route('admin.feeds.hide', $feed->id) }}" method="POST" class="inline" onsubmit="return confirm('Hide this post?')">
                                            @csrf
                                            <button 
                                                type="submit"
                                                class="p-2 text-slate-600 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-200"
                                                title="Hide Post"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-4.803m5.596-3.856a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0m7.111 0a10.05 10.05 0 01-15.937 4.803 10.05 10.05 0 0115.937-4.803z M9.73 12a2.25 2.25 0 100 2.25M3 3l18 18"/></svg>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.feeds.show', $feed->id) }}" method="POST" class="inline" onsubmit="return confirm('Show this post?')">
                                            @csrf
                                            @method('POST')
                                            <button 
                                                type="submit"
                                                class="p-2 text-slate-600 hover:text-green-600 hover:bg-green-50 rounded-lg transition-200"
                                                title="Show Post"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Delete -->
                                    <form action="{{ route('admin.feeds.destroy', $feed->id) }}" method="POST" class="inline" onsubmit="return confirm('Permanently delete this post? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit"
                                            class="p-2 text-slate-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-200"
                                            title="Delete Post"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-between">
                <div class="text-sm text-slate-600">
                    @if($feeds->total() > 0)
                        Showing <span class="font-medium">{{ ($feeds->currentPage() - 1) * $feeds->perPage() + 1 }}</span> to <span class="font-medium">{{ ($feeds->currentPage() - 1) * $feeds->perPage() + $feeds->count() }}</span> of <span class="font-medium">{{ $feeds->total() }}</span> results
                    @else
                        No results
                    @endif
                </div>
                <div class="flex gap-2">
                    {{ $feeds->links() }}
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="px-6 py-12 text-center">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 mb-1">No feeds found</h3>
                <p class="text-sm text-slate-600">No activity feeds match your current filters.</p>
            </div>
        @endif
    </div>
</div>
@endsection
