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

    <!-- Search & Filter Section -->
    <div class="space-y-3">
        <!-- Search Box -->
        <form action="{{ route('feed.search') }}" method="GET" class="relative">
            <input 
                type="text" 
                name="q" 
                value="{{ request('q', '') }}"
                placeholder="Search postingan..." 
                class="w-full bg-white border border-gray-200 rounded-full pl-4 pr-12 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-green-400"
            >
            <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-green-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </button>
        </form>

        <!-- Category Tabs -->
        <div class="flex gap-2 overflow-x-auto pb-2 -mx-4 px-4 lg:mx-0 lg:px-0 hide-scrollbar">
            <a href="{{ route('feed', ['filter' => 'all']) }}" class="px-4 py-2 bg-white border {{ request('filter', 'all') === 'all' ? 'border-green-200 text-green-700' : 'border-gray-100 text-gray-600 hover:border-gray-200' }} rounded-full text-sm font-semibold whitespace-nowrap shadow-sm transition-colors">
                🌍 All
            </a>
            <a href="{{ route('feed', ['filter' => 'challenge']) }}" class="px-4 py-2 bg-white border {{ request('filter') === 'challenge' ? 'border-green-200 text-green-700' : 'border-gray-100 text-gray-600 hover:border-gray-200' }} rounded-full text-sm font-medium whitespace-nowrap shadow-sm transition-colors">
                ✅ Challenges
            </a>
            <a href="{{ route('feed', ['filter' => 'badge']) }}" class="px-4 py-2 bg-white border {{ request('filter') === 'badge' ? 'border-green-200 text-green-700' : 'border-gray-100 text-gray-600 hover:border-gray-200' }} rounded-full text-sm font-medium whitespace-nowrap shadow-sm transition-colors">
                🏅 Badges
            </a>
            <a href="{{ route('feed', ['filter' => 'streak']) }}" class="px-4 py-2 bg-white border {{ request('filter') === 'streak' ? 'border-green-200 text-green-700' : 'border-gray-100 text-gray-600 hover:border-gray-200' }} rounded-full text-sm font-medium whitespace-nowrap shadow-sm transition-colors">
                🔥 Streaks
            </a>
        </div>
    </div>

    <!-- Feed Items -->
    @if($feeds->count() > 0)
        @foreach($feeds as $feed)
            <!-- Post Card with Link to Detail -->
            <div onclick="window.location.href='{{ route('feed.show', $feed) }}'" class="bg-white rounded-2xl border border-gray-100 shadow-sm cursor-pointer hover:shadow-md hover:border-gray-200 transition-all relative">
                <!-- Header -->
                <div class="flex items-center justify-between p-4 pb-3" onclick="event.stopPropagation();">
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

                    <!-- Edit/Delete Actions (only for post owner) -->
                    @if($feed->user_id === auth()->id())
                        <div class="flex gap-2">
                            <!-- Edit Button -->
                            <a href="{{ route('feed.edit', $feed) }}" class="p-2 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Edit post">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>

                            <!-- Delete Button with Modal Trigger -->
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

                <div class="px-4 py-3 bg-gradient-to-r from-green-50 to-emerald-50 border-t border-gray-100 flex items-center justify-between text-xs rounded-b-2xl" id="engagement-{{ $feed->id }}">
                    <div class="flex items-center gap-4">
                        <!-- Like Form -->
                        <form action="{{ route('feed.like.toggle', $feed->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center gap-1.5 hover:text-red-600 transition-colors cursor-pointer {{ $feed->likes->contains('user_id', auth()->id()) ? 'text-red-500' : 'text-gray-500' }}">
                                <svg class="w-4 h-4" fill="{{ $feed->likes->contains('user_id', auth()->id()) ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                <span class="font-medium">{{ $feed->likes_count }}</span>
                            </button>
                        </form>
                        <div class="flex items-center gap-1.5 cursor-pointer text-gray-500 hover:text-blue-600 transition-colors" onclick="document.getElementById('comments-{{ $feed->id }}').classList.toggle('hidden'); document.getElementById('engagement-{{ $feed->id }}').classList.toggle('rounded-b-2xl');">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            <span class="font-medium">{{ $feed->comments_count }} Comments</span>
                        </div>
                    </div>

                    <!-- Share Dropdown -->
                    <div class="relative" onclick="event.stopPropagation(); document.getElementById('share-menu-{{ $feed->id }}').classList.toggle('hidden')">
                        <button class="flex items-center gap-1.5 cursor-pointer text-gray-500 hover:text-green-600 hover:bg-green-50 px-3 py-1.5 -mr-2 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-green-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                            <span class="font-medium">Share</span>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="share-menu-{{ $feed->id }}" class="hidden absolute right-0 bottom-full mb-2 w-52 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50">
                            <div class="px-3 py-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Share ke...</div>
                            
                            <a href="https://api.whatsapp.com/send?text=Check out this eco action! {{ route('feed.show', $feed->id) }}" target="_blank" class="flex items-center gap-3 px-4 py-2 hover:bg-gray-50 transition-colors">
                                <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0 text-white">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                </div>
                                <span class="text-sm text-gray-700 font-medium">WhatsApp</span>
                            </a>
                            
                            <a href="https://twitter.com/intent/tweet?url={{ route('feed.show', $feed->id) }}&text=Check out this eco action on TerraVerde!" target="_blank" class="flex items-center gap-3 px-4 py-2 hover:bg-gray-50 transition-colors">
                                <div class="w-6 h-6 bg-black rounded-full flex items-center justify-center flex-shrink-0 text-white">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                </div>
                                <span class="text-sm text-gray-700 font-medium">Twitter / X</span>
                            </a>

                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ route('feed.show', $feed->id) }}" target="_blank" class="flex items-center gap-3 px-4 py-2 hover:bg-gray-50 transition-colors">
                                <div class="w-6 h-6 bg-[#1877F2] rounded-full flex items-center justify-center flex-shrink-0 text-white">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </div>
                                <span class="text-sm text-gray-700 font-medium">Facebook</span>
                            </a>

                            <a href="https://t.me/share/url?url={{ route('feed.show', $feed->id) }}&text=Check out this eco action!" target="_blank" class="flex items-center gap-3 px-4 py-2 hover:bg-gray-50 transition-colors">
                                <div class="w-6 h-6 bg-[#0088cc] rounded-full flex items-center justify-center flex-shrink-0 text-white pl-0.5">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.223-.548.223l.188-2.85 5.18-4.68c.223-.198-.054-.31-.346-.11l-6.4 4.02-2.76-.89c-.6-.188-.612-.6.126-.89l10.814-4.17c.502-.18.966.115.806.915z"/></svg>
                                </div>
                                <span class="text-sm text-gray-700 font-medium">Telegram</span>
                            </a>

                            <a href="https://discord.com/channels/@me" target="_blank" class="flex items-center gap-3 px-4 py-2 hover:bg-gray-50 transition-colors">
                                <div class="w-6 h-6 bg-[#5865F2] rounded-full flex items-center justify-center flex-shrink-0 text-white">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.317 4.3698a19.7913 19.7913 0 00-4.8851-1.5152.0741.0741 0 00-.0785.0371c-.211.3753-.4447.8648-.6083 1.2495-1.8447-.2762-3.68-.2762-5.4868 0-.1636-.3933-.4058-.8742-.6177-1.2495a.077.077 0 00-.0785-.037 19.7363 19.7363 0 00-4.8852 1.515.0699.0699 0 00-.0321.0277C.5334 9.0458-.319 13.5799.0992 18.0578a.0824.0824 0 00.0312.0561c2.0528 1.5076 4.0413 2.4228 5.9929 3.0294a.0777.0777 0 00.0842-.0276c.4616-.6304.8731-1.2952 1.226-1.9942a.076.076 0 00-.0416-.1057c-.6528-.2476-1.2743-.5495-1.8722-.8923a.077.077 0 01-.0076-.1277c.1258-.0943.2517-.1923.3718-.2914a.0743.0743 0 01.0776-.0105c3.9278 1.7933 8.18 1.7933 12.0614 0a.0739.0739 0 01.0785.0095c.1202.099.246.1981.3728.2924a.077.077 0 01-.0066.1276 12.2986 12.2986 0 01-1.873.8914.0766.0766 0 00-.0407.1067c.3604.698.7719 1.3628 1.225 1.9932a.076.076 0 00.0842.0286c1.961-.6067 3.9495-1.5219 6.0023-3.0294a.077.077 0 00.0313-.0552c.5004-5.177-.8382-9.6739-3.5485-13.6604a.061.061 0 00-.0312-.0286zM8.02 15.3312c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9555-2.4189 2.157-2.4189 1.2108 0 2.1757 1.0952 2.1568 2.419 0 1.3332-.9555 2.4189-2.1569 2.4189zm7.9748 0c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9554-2.4189 2.1569-2.4189 1.2108 0 2.1757 1.0952 2.1568 2.419 0 1.3332-.946 2.4189-2.1568 2.4189Z"/></svg>
                                </div>
                                <span class="text-sm text-gray-700 font-medium">Discord</span>
                            </a>
                            
                            <a href="https://social-plugins.line.me/lineit/share?url={{ route('feed.show', $feed->id) }}" target="_blank" class="flex items-center gap-3 px-4 py-2 hover:bg-gray-50 transition-colors">
                                <div class="w-6 h-6 bg-[#00B900] rounded-full flex items-center justify-center flex-shrink-0 text-white">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 10.304c0-5.369-5.383-9.738-12-9.738-6.616 0-12 4.369-12 9.738 0 4.814 3.54 8.868 8.432 9.605.328.069.771.21.884.512.102.274.066.699.032.996 0 0-.106.634-.129.772-.039.231-.183.91.802.493.985-.417 5.326-3.136 7.234-5.337C22.614 15.342 24 12.981 24 10.304zM7.55 12.753H4.498a.598.598 0 01-.599-.599V7.811c0-.33.268-.598.599-.598h3.052a.599.599 0 01.599.599v1.282a.598.598 0 01-.599.599H5.696v2.46h1.854a.598.598 0 01.599.598v.402a.598.598 0 01-.599.6zM9.479 12.154c0 .33-.269.599-.599.599h-.402a.598.598 0 01-.599-.599V7.811c0-.33.269-.598.599-.598h.402c.33 0 .599.268.599.598v4.343zm4.618 0c0 .33-.269.599-.599.599h-.402a.598.598 0 01-.599-.599v-2.316l-1.921 2.766a.584.584 0 01-.482.268h-.001a.598.598 0 01-.599-.599V7.811c0-.33.268-.598.598-.598h.402c.33 0 .599.268.599.598v2.327l1.927-2.775a.582.582 0 01.479-.268h.001c.33 0 .599.268.599.598v4.343zm5.405-2.062h-1.855v1.464h3.053a.598.598 0 01.599.599v.402a.598.598 0 01-.599.599h-4.053a.598.598 0 01-.599-.599V7.811c0-.33.268-.598.599-.598h4.053a.598.598 0 01.599.598v.402a.598.598 0 01-.599.6H17.65v1.28h1.855a.598.598 0 01.599.598v.402a.598.598 0 01-.599.6z"/></svg>
                                </div>
                                <span class="text-sm text-gray-700 font-medium">LINE</span>
                            </a>

                            <div class="h-px bg-gray-100 my-1"></div>

                            <button onclick="navigator.clipboard.writeText('{{ route('feed.show', $feed->id) }}'); alert('Link copied to clipboard!');" class="w-full flex items-center gap-3 px-4 py-2 hover:bg-gray-50 transition-colors text-left">
                                <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0 text-gray-600">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                </div>
                                <span class="text-sm text-gray-700 font-medium">Salin Tautan</span>
                            </button>

                            <button onclick="if(navigator.share) { navigator.share({ title: 'Eco Platform', url: '{{ route('feed.show', $feed->id) }}' }) } else { alert('Web Share API is not supported in your browser.'); };" class="w-full flex items-center gap-3 px-4 py-2 hover:bg-gray-50 transition-colors text-left">
                                <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0 text-gray-600">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                </div>
                                <span class="text-sm text-gray-700 font-medium">Bagikan via...</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Comments Section (Hidden by default) -->
                <div id="comments-{{ $feed->id }}" class="hidden bg-gray-50 border-t border-gray-100 px-4 py-3 rounded-b-2xl">
                    
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
                                                <div class="flex items-center gap-2">
                                                    <span class="text-[9px] text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                                    @if($comment->user_id === auth()->id())
                                                        <button 
                                                            onclick="openDeleteCommentModal('{{ $feed->id }}', '{{ $comment->id }}')"
                                                            class="p-0.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors"
                                                            title="Delete comment"
                                                        >
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                        </button>
                                                    @endif
                                                </div>
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
                                        <form id="reply-form-{{ $comment->id }}" action="{{ route('feed.comments.store', $feed->id) }}" method="POST" class="hidden flex gap-2 mt-2">
                                            @csrf
                                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                            <div class="flex-1 relative">
                                                <input 
                                                    type="text" 
                                                    id="reply-input-{{ $comment->id }}"
                                                    name="content" 
                                                    placeholder="Reply to {{ $comment->user->name }}..." 
                                                    class="w-full text-xs bg-white border border-gray-200 rounded-full px-3 py-1.5 focus:outline-none focus:ring-1 focus:ring-green-400 focus:border-green-400"
                                                >
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
                                                            <!-- Reply to Reply Button -->
                                                            <div class="flex justify-end mt-1 px-1">
                                                                <button type="button" onclick="showReplyForm('{{ $comment->id }}', '{{ str_replace(' ', '', $reply->user->name) }}')" class="text-[9px] font-semibold text-gray-500 hover:text-gray-700">Reply</button>
                                                            </div>
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
                    <form action="{{ route('feed.comments.store', $feed->id) }}" method="POST" class="flex gap-2 mt-2 pt-2 border-t border-gray-200">
                        @csrf
                        <div class="flex-1 relative">
                            <input 
                                type="text" 
                                name="content" 
                                placeholder="Write a comment..." 
                                class="w-full text-xs bg-white border border-gray-200 rounded-full px-3 py-2 focus:outline-none focus:ring-1 focus:ring-green-400 focus:border-green-400"
                            >
                        </div>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white p-2 rounded-full flex-shrink-0 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" x2="11" y1="2" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                        </button>
                    </form>
                    
                    @error('content') <p class="text-[10px] text-red-500 mt-1 pl-2">{{ $message }}</p> @enderror
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
// Share dropdown functions
function toggleShareMenu(id) {
    const menu = document.getElementById(id);
    if (!menu) return;
    const isHidden = menu.classList.contains('hidden');
    // Close all other open share menus first
    document.querySelectorAll('[id^="share-menu-"]').forEach(function(el) {
        el.classList.add('hidden');
    });
    if (isHidden) {
        menu.classList.remove('hidden');
    }
}

// Close share menus when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('[id^="share-menu-"]') && !e.target.closest('.relative')) {
        document.querySelectorAll('[id^="share-menu-"]').forEach(function(el) {
            el.classList.add('hidden');
        });
    }
});

function showReplyForm(commentId, userName) {
    const form = document.getElementById('reply-form-' + commentId);
    const input = document.getElementById('reply-input-' + commentId);
    if (form && input) {
        form.classList.remove('hidden');
        input.value = '@' + userName + ' ';
        input.focus();
    }
}

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

// Delete Modal Functions
let deleteModalData = { feedId: null, userName: '' };

function openDeleteModal(feedId, userName) {
    deleteModalData = { feedId, userName };
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteModal').classList.remove('flex');
    document.body.style.overflow = 'auto';
    deleteModalData = { feedId: null, userName: '' };
}

function confirmDelete() {
    if (deleteModalData.feedId) {
        document.getElementById('deleteForm-' + deleteModalData.feedId).submit();
    }
}

let deleteCommentModalData = { feedId: null, commentId: null };

function openDeleteCommentModal(feedId, commentId) {
    deleteCommentModalData = { feedId, commentId };
    document.getElementById('deleteCommentModal').classList.remove('hidden');
    document.getElementById('deleteCommentModal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeDeleteCommentModal() {
    document.getElementById('deleteCommentModal').classList.add('hidden');
    document.getElementById('deleteCommentModal').classList.remove('flex');
    document.body.style.overflow = 'auto';
    deleteCommentModalData = { feedId: null, commentId: null };
}

function confirmDeleteComment() {
    if (deleteCommentModalData.feedId && deleteCommentModalData.commentId) {
        const form = document.getElementById('deleteCommentForm');
        form.action = '/feed/' + deleteCommentModalData.feedId + '/comments/' + deleteCommentModalData.commentId;
        form.submit();
    }
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('deleteModal');
    const commentModal = document.getElementById('deleteCommentModal');
    
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeDeleteModal();
            }
        });
    }

    if (commentModal) {
        commentModal.addEventListener('click', function(e) {
            if (e.target === commentModal) {
                closeDeleteCommentModal();
            }
        });
    }

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
            closeDeleteCommentModal();
        }
    });
});
</script>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-sm w-full animate-in fade-in zoom-in-95">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-red-50 to-red-100 px-6 py-4 border-b border-red-200 rounded-t-2xl">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 0v2m0-2v-2m0-4v-2M6.228 6.228l1.414 1.414m9.9-1.414l1.414-1.414m0 9.9l1.414 1.414m-1.414 9.9l-1.414-1.414m9.9-1.414l1.414 1.414m-1.414 9.9l-1.414-1.414M6.228 17.772l1.414 1.414m9.9-1.414l1.414 1.414m-9.9-15.85a9 9 0 1018 0 9 9 0 00-18 0z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Delete Post?</h2>
                    <p class="text-sm text-red-600 font-medium">This action cannot be undone</p>
                </div>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="px-6 py-4">
            <p class="text-gray-700 text-sm leading-relaxed mb-3">
                Are you sure you want to delete this post? This will permanently remove the post and all associated media files.
            </p>
            <div class="bg-red-50 border border-red-200 rounded-lg p-3 flex gap-2">
                <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <p class="text-xs text-red-700 font-medium">This includes all media and cannot be recovered.</p>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 rounded-b-2xl border-t border-gray-200 flex gap-3">
            <button 
                onclick="closeDeleteModal()"
                class="flex-1 px-4 py-2.5 bg-white border border-gray-300 text-gray-900 font-semibold rounded-lg hover:bg-gray-100 transition-colors"
            >
                Cancel
            </button>
            <button 
                onclick="confirmDelete()"
                class="flex-1 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors flex items-center justify-center gap-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Delete Post
            </button>
        </div>
    </div>
</div>

<!-- Hidden Delete Forms (one for each feed) -->
@foreach($feeds as $feed)
    @if($feed->user_id === auth()->id())
        <form id="deleteForm-{{ $feed->id }}" action="{{ route('feed.destroy', $feed) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endif
@endforeach

<!-- Delete Comment Modal -->
<div id="deleteCommentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-sm w-full animate-in fade-in zoom-in-95">
        <div class="bg-red-50 px-6 py-4 border-b border-red-200 rounded-t-2xl flex items-center gap-3">
            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 0v2m0-2v-2m0-4v-2M6.228 6.228l1.414 1.414m9.9-1.414l1.414-1.414m0 9.9l1.414 1.414m-1.414 9.9l-1.414-1.414m9.9-1.414l1.414 1.414m-1.414 9.9l-1.414-1.414M6.228 17.772l1.414 1.414m9.9-1.414l1.414 1.414m-9.9-15.85a9 9 0 1018 0 9 9 0 00-18 0z"></path>
                </svg>
            </div>
            <h2 class="text-lg font-bold text-gray-900">Delete Comment?</h2>
        </div>
        <div class="px-6 py-4">
            <p class="text-gray-700 text-sm">Are you sure you want to delete this comment? This action cannot be undone.</p>
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

<!-- Hidden Delete Comment Form -->
<form id="deleteCommentForm" action="" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

@endsection
