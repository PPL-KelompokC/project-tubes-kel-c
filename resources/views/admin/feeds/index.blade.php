@extends('admin.layouts.app')

@section('title', 'Activity Feed Management')
@section('page_title', 'Activity Feed Management')
@section('page_subtitle', 'Monitor and moderate all user activity feeds across the platform.')

@section('content')
<div class="flex flex-col lg:flex-row gap-8 max-w-7xl mx-auto">
    <!-- Left Sidebar: Activity & Member List -->
    <div class="w-full lg:w-64 flex-shrink-0">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden sticky top-24">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 text-sm">Activity</h3>
            </div>
            <div class="p-4 border-b border-slate-100 flex items-center justify-between text-xs font-semibold text-slate-500">
                <span>Member list <span class="text-slate-400 font-normal">{{ $members->count() }}</span></span>
                <span>Activities</span>
            </div>
            <div class="max-h-[60vh] overflow-y-auto custom-scrollbar p-2">
                @forelse($members as $member)
                    <div class="flex items-center justify-between p-2 hover:bg-slate-50 rounded-xl transition-colors">
                        <div class="flex items-center gap-3">
                            @if($member->avatar)
                                <img src="{{ $member->avatar }}" alt="{{ $member->name }}" class="w-8 h-8 rounded-full object-cover ring-2 ring-emerald-50">
                            @else
                                @php
                                    $colors = ['bg-orange-500', 'bg-green-500', 'bg-purple-500', 'bg-blue-900', 'bg-emerald-500'];
                                    $color = $colors[$loop->index % count($colors)];
                                @endphp
                                <div class="w-8 h-8 rounded-full {{ $color }} flex items-center justify-center text-white font-bold text-xs">
                                    {{ substr($member->name, 0, 1) }}
                                </div>
                            @endif
                            <span class="text-sm font-medium text-slate-700 truncate max-w-[100px]" title="{{ $member->name }}">{{ $member->name }}</span>
                        </div>
                        <span class="text-xs font-bold text-slate-400">{{ $member->feeds_count }}</span>
                    </div>
                @empty
                    <div class="p-4 text-center text-sm text-slate-500">No members found</div>
                @endforelse
            </div>
            <div class="p-3 border-t border-slate-100 text-center text-xs text-slate-400 font-medium bg-slate-50">
                Showing top active members
            </div>
        </div>
    </div>

    <!-- Main Content: Feeds -->
    <div class="flex-1 max-w-3xl space-y-6">
        <!-- Top Controls -->
        <div class="flex items-center justify-between gap-4">
            <div class="flex-1 relative">
                <form method="GET" action="{{ route('admin.feeds.search') }}" class="flex">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input 
                        type="text" 
                        name="q" 
                        placeholder="Search post by...." 
                        value="{{ $query ?? '' }}"
                        class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 shadow-sm"
                    />
                </form>
            </div>
            <button class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-medium hover:bg-slate-50 shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/></svg>
                Sort
            </button>
        </div>

        <!-- Filter Tabs & New Post Button -->
        <div class="flex items-center justify-between border-b border-slate-200 pb-2">
            <div class="flex gap-6">
                <a href="{{ route('admin.feeds.index') }}" class="flex items-center gap-2 pb-2 border-b-2 {{ !isset($status) || $status === 'all' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-slate-500 hover:text-slate-700' }} font-bold text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    All Post <span class="bg-emerald-100 text-emerald-700 text-[10px] px-1.5 py-0.5 rounded-md">{{ $stats['total'] }}</span>
                </a>
                <a href="#" class="flex items-center gap-2 pb-2 border-b-2 border-transparent text-slate-500 hover:text-slate-700 font-bold text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Media <span class="bg-slate-100 text-slate-600 text-[10px] px-1.5 py-0.5 rounded-md">0</span>
                </a>
            </div>
            
            <button onclick="document.getElementById('composerSection').classList.toggle('hidden')" class="bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold px-4 py-2 rounded-full transition-all shadow-sm flex items-center gap-1">
                New Post +
            </button>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <p class="text-sm text-emerald-700 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Composer Section (Hidden by default) -->
        <div id="composerSection" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 hidden transition-all">
            <form action="{{ route('feed.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="flex gap-4">
                    <div class="w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 border border-slate-200 rounded-2xl p-3 focus-within:ring-2 focus-within:ring-emerald-500/20 focus-within:border-emerald-500 transition-all">
                        <textarea
                            name="caption"
                            placeholder="What do you want to announce?....."
                            class="w-full text-sm bg-transparent border-none resize-none focus:ring-0 p-0"
                            rows="3"
                            required
                        >{{ old('caption') }}</textarea>
                        
                        <div id="mediaPreviewAdmin" class="mt-3 grid grid-cols-2 gap-2 hidden">
                            <template id="mediaTemplateAdmin">
                                <div class="relative rounded-lg overflow-hidden border border-slate-200 bg-slate-50">
                                    <img class="w-full h-24 object-cover" />
                                    <button type="button" class="absolute top-1 right-1 bg-rose-500 text-white rounded-full p-1 hover:bg-rose-600" onclick="removeMediaAdmin(this)">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between pl-14">
                    <label class="p-2 text-slate-400 hover:text-emerald-500 hover:bg-emerald-50 rounded-xl transition-colors cursor-pointer" title="Add Image">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <input type="file" name="media[]" accept="image/*,video/*" multiple class="hidden" onchange="handleMediaSelectAdmin(this)"/>
                    </label>
                    
                    <button type="submit" class="bg-emerald-400 hover:bg-emerald-500 text-white text-xs font-bold px-6 py-2.5 rounded-full transition-all flex items-center gap-2 shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Post
                    </button>
                </div>
            </form>
        </div>

        <h2 class="text-lg font-bold text-slate-800 pt-2">Today</h2>

        <!-- Feed List -->
        <div class="space-y-6 pb-10">
            @forelse($feeds as $feed)
                <div class="bg-white rounded-3xl border {{ $feed->status === 'hidden' ? 'border-orange-300 opacity-70' : 'border-slate-100' }} shadow-sm overflow-visible relative">
                    
                    @if($feed->status === 'hidden')
                        <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-orange-100 text-orange-700 text-[10px] font-bold px-3 py-1 rounded-full border border-orange-200 z-10 shadow-sm">
                            HIDDEN POST
                        </div>
                    @endif

                    <!-- Header -->
                    <div class="flex items-start justify-between p-5 pb-3">
                        <div class="flex items-center gap-3">
                            @if($feed->user->avatar)
                                <img src="{{ $feed->user->avatar }}" alt="{{ $feed->user->name }}" class="w-12 h-12 rounded-full object-cover ring-2 ring-emerald-50" />
                            @else
                                <div class="w-12 h-12 rounded-full bg-slate-800 flex items-center justify-center text-white font-bold text-lg">
                                    {{ substr($feed->user->name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <h3 class="font-bold text-slate-800 leading-tight">{{ $feed->user->name }}</h3>
                                <div class="flex items-center gap-2 mt-0.5">
                                    @if($feed->feed_type === 'challenge_complete')
                                        <span class="bg-emerald-100 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded-md flex items-center gap-1">
                                            <svg class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                            Challenge Complete
                                        </span>
                                    @elseif($feed->feed_type === 'badge_earned')
                                        <span class="bg-amber-100 text-amber-700 text-[10px] font-bold px-2 py-0.5 rounded-md flex items-center gap-1">
                                            🏆 Badge Earned
                                        </span>
                                    @endif
                                    <span class="text-xs text-slate-400">{{ $feed->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Dropdown Menu -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" @click.outside="open = false" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-full transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/></svg>
                            </button>
                            <div x-show="open" style="display: none;" class="absolute right-0 mt-1 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-1 z-20"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95">
                                
                                <a href="{{ route('admin.feeds.show', $feed->id) }}" class="block w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                    View Details
                                </a>

                                @if($feed->status === 'active')
                                    <form action="{{ route('admin.feeds.hide', $feed->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-orange-600 hover:bg-orange-50 font-medium">
                                            Hide Post
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.feeds.unhide', $feed->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-emerald-600 hover:bg-emerald-50 font-medium">
                                            Show Post
                                        </button>
                                    </form>
                                @endif

                                <div class="h-px bg-slate-100 my-1"></div>

                                <button type="button" onclick="confirmDeleteFeed({{ $feed->id }})" class="block w-full text-left px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 font-medium">
                                    Delete Post
                                </button>
                                <form id="deleteForm-{{ $feed->id }}" action="{{ route('admin.feeds.destroy', $feed->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="px-5 pb-3">
                        <p class="text-slate-800 text-[15px] leading-relaxed break-words">{!! nl2br(e($feed->caption)) !!}</p>
                    </div>

                    <!-- Media -->
                    @if($feed->media && count($feed->media) > 0)
                        <div class="px-5 pb-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @foreach($feed->media as $media)
                                    @php
                                        $url = is_array($media) ? ($media['url'] ?? $media) : $media;
                                        $type = is_array($media) ? ($media['type'] ?? 'image') : 'image';
                                    @endphp
                                    @if($type === 'video')
                                        <video controls class="w-full h-64 object-cover rounded-2xl bg-slate-100 border border-slate-200">
                                            <source src="{{ $url }}" />
                                        </video>
                                    @else
                                        <img src="{{ $url }}" class="w-full h-64 object-cover rounded-2xl border border-slate-200" />
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Context Card (e.g. Points earned) -->
                    @if($feed->feed_type === 'challenge_complete' || $feed->feed_type === 'badge_earned')
                    <div class="px-5 pb-4">
                        <div class="bg-emerald-50/50 border border-emerald-100 rounded-xl p-3 flex items-center justify-between">
                            <div class="flex items-center gap-4 text-xs font-bold">
                                @if($feed->feed_type === 'challenge_complete')
                                    <span class="text-amber-500 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        +50 pts
                                    </span>
                                    <span class="text-emerald-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                        Eco Action
                                    </span>
                                @else
                                    <span class="text-amber-600 flex items-center gap-1 text-sm">
                                        🏅 Badge Master
                                    </span>
                                    <span class="text-slate-500 font-medium">+300 XP</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Footer / Engagement -->
                    <div class="px-5 py-3 border-t border-slate-100 flex items-center justify-between text-sm text-slate-500">
                        <div class="flex items-center gap-6">
                            <button class="flex items-center gap-2 hover:text-rose-500 transition-colors">
                                <svg class="w-5 h-5 {{ $feed->likes_count > 0 ? 'text-rose-500 fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                <span class="font-medium {{ $feed->likes_count > 0 ? 'text-rose-500' : '' }}">{{ $feed->likes_count ?: '' }}</span>
                            </button>
                            <button class="flex items-center gap-2 hover:text-emerald-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                <span class="font-medium">{{ $feed->comments_count ?: '' }}</span>
                            </button>
                        </div>
                        <button class="flex items-center gap-2 hover:text-slate-700 transition-colors font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                            Share
                        </button>
                    </div>
                </div>
            @empty
                <!-- Empty State -->
                <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center shadow-sm">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-1">No Activity Found</h3>
                    <p class="text-sm text-slate-500">There are no posts to display in the feed yet.</p>
                </div>
            @endforelse

            <!-- Pagination -->
            @if($feeds->hasPages())
                <div class="pt-4">
                    {{ $feeds->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function handleMediaSelectAdmin(input) {
    const files = input.files;
    const preview = document.getElementById('mediaPreviewAdmin');
    const template = document.getElementById('mediaTemplateAdmin');
    
    preview.innerHTML = '';
    
    if (files.length > 0) {
        preview.classList.remove('hidden');
        
        Array.from(files).forEach((file, index) => {
            if (index < 5) {
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

function removeMediaAdmin(btn) {
    btn.closest('div').remove();
    const preview = document.getElementById('mediaPreviewAdmin');
    if (preview.children.length === 0) {
        preview.classList.add('hidden');
    }
}

function confirmDeleteFeed(feedId) {
    Swal.fire({
        title: 'Delete Feed Post?',
        text: 'This action cannot be undone. The post will be permanently deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Delete Post',
        cancelButtonText: 'Cancel',
        allowOutsideClick: false,
        allowEscapeKey: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit the hidden form
            document.getElementById(`deleteForm-${feedId}`).submit();
        }
    });
}

// Show success alert when page loads if there's a success message
document.addEventListener('DOMContentLoaded', function() {
    const successMessage = document.querySelector('[role="alert"]');
    if (successMessage && successMessage.textContent.includes('deleted')) {
        const message = successMessage.textContent.trim();
        setTimeout(() => {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: message,
                confirmButtonColor: '#10b981',
                confirmButtonText: 'OK'
            });
            // Optional: remove the old alert after showing SweetAlert
            successMessage.remove();
        }, 100);
    }
});
</script>
@endsection
