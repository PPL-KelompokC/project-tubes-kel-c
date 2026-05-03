@php
    $user = Auth::user();
    $currentUser = [
        'name' => $user->name,
        'avatar' => $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=059669&color=fff',
        'level' => floor($user->points / 1000) + 1,
        'xp' => $user->points % 1000,
        'xpToNextLevel' => 1000,
        'streak' => $user->streak,
        'totalPoints' => $user->points,
        'rank' => \App\Models\User::where('points', '>', $user->points)->count() + 1
    ];
    $progressPct = round(($currentUser['xp'] / $currentUser['xpToNextLevel']) * 100);
    
    $navItems = [
        ['label' => 'Dashboard', 'path' => 'dashboard', 'icon' => 'layout-dashboard', 'group' => 'main'],
        ['label' => 'Challenges', 'path' => 'challenges', 'icon' => 'list-checks', 'group' => 'main'],
        ['label' => 'Carbon Tracker', 'path' => 'carbon', 'icon' => 'leaf', 'group' => 'main'],
        ['label' => 'Leaderboard', 'path' => 'leaderboard', 'icon' => 'trophy', 'group' => 'social'],
        ['label' => 'Activity Feed', 'path' => 'feed', 'icon' => 'activity', 'group' => 'social'],
        ['label' => 'Community Map', 'path' => '#', 'icon' => 'map', 'group' => 'social'],
        ['label' => 'My Profile', 'path' => 'profile', 'icon' => 'star', 'group' => 'personal'],
        ['label' => 'Badges', 'path' => '#', 'icon' => 'shield-check', 'group' => 'personal'],
        ['label' => 'My Stats', 'path' => '#', 'icon' => 'trending-up', 'group' => 'personal'],
        ['label' => 'Rewards', 'path' => '#', 'icon' => 'gift', 'group' => 'personal'],
        ['label' => 'Learn', 'path' => '#', 'icon' => 'book-open', 'group' => 'learn'],
        ['label' => 'Refer Friends', 'path' => '#', 'icon' => 'share-2', 'group' => 'learn'],
        ['label' => 'Notifications', 'path' => '#', 'icon' => 'bell', 'group' => 'system'],
    ];

    // Admin panel link — only visible to admins
    if ($user->role === 'admin') {
        $navItems[] = ['label' => 'Admin Panel', 'path' => 'admin.dashboard', 'icon' => 'settings', 'group' => 'system'];
    }

    $groups = [
        ['id' => 'main', 'label' => 'EXPLORE'],
        ['id' => 'social', 'label' => 'COMMUNITY'],
        ['id' => 'personal', 'label' => 'MY JOURNEY'],
        ['id' => 'learn' , 'label' => 'GROW'],
        ['id' => 'system', 'label' => 'SYSTEM'],
    ];
@endphp

<!-- Mobile overlay -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-40 lg:hidden hidden" onclick="toggleSidebar()"></div>

<!-- Sidebar -->
<aside id="sidebar" class="fixed top-0 left-0 h-full z-50 flex flex-col bg-white border-r border-gray-100 sidebar-transition -translate-x-full lg:translate-x-0 lg:static lg:z-auto w-64 shadow-xl lg:shadow-none">
    <!-- Logo -->
    <div class="flex items-center gap-3 px-4 py-5 border-b border-gray-100">
        <div class="w-10 h-10 flex items-center justify-center flex-shrink-0 transition-transform hover:scale-105 duration-300">
            <img src="{{ asset('image/icon-terraverde.png') }}" alt="TerraVerde Logo" class="w-full h-full object-contain drop-shadow-sm">
        </div>
        <div class="flex flex-col">
            <span class="text-base font-black text-gray-900 leading-none tracking-tight">TerraVerde</span>
            <span class="text-[10px] font-bold text-green-600 uppercase tracking-widest mt-1">Eco Platform</span>
        </div>
        <button onclick="toggleSidebar()" class="ml-auto lg:hidden text-gray-400 hover:text-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </button>
    </div>

    <!-- User Quick Info -->
    <div class="px-3 py-3 border-b border-gray-100">
        <div class="flex items-center gap-2.5">
            <img src="{{ $currentUser['avatar'] }}" alt="{{ $currentUser['name'] }}" class="w-9 h-9 rounded-full object-cover ring-2 ring-green-200" />
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-900 truncate">{{ $currentUser['name'] }}</p>
                <div class="flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="currentColor" class="text-orange-500"><path d="M12 2c0 0-5.5 5-5.5 10.5A5.5 5.5 0 0 0 12 18a5.5 5.5 0 0 0 5.5-5.5C17.5 7 12 2 12 2Zm0 14a3.5 3.5 0 0 1-3.5-3.5C8.5 9.4 12 5.5 12 5.5s3.5 3.9 3.5 7A3.5 3.5 0 0 1 12 16Z"/></svg>
                    <span class="text-xs text-orange-600 font-semibold">{{ $currentUser['streak'] }} day streak</span>
                </div>
            </div>
            <div class="bg-green-100 text-green-700 text-xs font-bold px-2 py-0.5 rounded-full">
                Lv.{{ $currentUser['level'] }}
            </div>
        </div>
        <!-- XP bar -->
        <div class="mt-2.5">
            <div class="flex justify-between items-center mb-1">
                <span class="text-[10px] text-gray-400 font-medium">XP Progress</span>
                <span class="text-[10px] text-green-600 font-semibold">{{ $currentUser['xp'] }} / {{ $currentUser['xpToNextLevel'] }}</span>
            </div>
            <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-green-400 to-emerald-500 rounded-full animate-xp-glow" style="width: {{ $progressPct }}%"></div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-3 px-2">
        @foreach($groups as $group)
            <div class="mb-3">
                <p class="text-[10px] font-bold text-gray-400 tracking-widest px-2 mb-1.5">{{ $group['label'] }}</p>
                @foreach(collect($navItems)->where('group', $group['id']) as $item)
                    @php $isActive = $item['path'] !== '#' && Request::routeIs($item['path']); @endphp
                    <a href="{{ $item['path'] === '#' ? '#' : route($item['path']) }}" class="flex items-center gap-3 px-2.5 py-2 rounded-xl mb-0.5 transition-all duration-150 {{ $isActive ? 'bg-green-600 text-white shadow-sm shadow-green-200' : 'text-gray-600 hover:bg-green-50 hover:text-green-700' }}">
                        <div class="relative flex-shrink-0">
                            <!-- Simple SVG Icons based on Lucide names -->
                            @include('layouts.partials.icons.' . $item['icon'], ['class' => 'w-4.5 h-4.5 ' . ($isActive ? 'text-white' : '')])
                            @if($item['path'] === 'notifications' || $item['label'] === 'Notifications')
                                <span class="absolute -top-1.5 -right-1.5 w-4 h-4 bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center animate-notif">3</span>
                            @endif
                        </div>
                        <span class="text-sm font-medium {{ $isActive ? 'text-white' : '' }}">{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </div>
        @endforeach
    </nav>

    <!-- Bottom points -->
    <div class="px-3 py-3 border-t border-gray-100">
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-3 border border-green-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] text-gray-500 font-medium">Total Points</p>
                    <p class="text-lg font-bold text-green-700">{{ number_format($currentUser['totalPoints']) }}</p>
                </div>
                <div class="bg-green-100 rounded-xl p-2.5 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
                </div>
            </div>
            <div class="mt-1.5 flex items-center gap-1.5">
                <div class="w-2 h-2 rounded-full bg-green-400"></div>
                <span class="text-[10px] text-gray-500">Rank #{{ $currentUser['rank'] }} worldwide</span>
            </div>
        </div>
        
        <!-- Logout Button -->
        <form action="{{ route('logout') }}" method="POST" class="mt-3">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-red-500 hover:bg-red-50 transition-all duration-150 group">
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:translate-x-0.5 transition-transform"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                </div>
                <span class="text-sm font-bold tracking-tight">Sign Out</span>
            </button>
        </form>
    </div>
</aside>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }
</script>
