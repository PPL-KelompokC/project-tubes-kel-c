@php
    $user = Auth::user();
    $currentUser = $user ? [
        'name' => $user->name,
        'avatar' => $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=059669&color=fff',
        'streak' => $user->streak,
        'totalPoints' => $user->points,
    ] : null;

    $pageTitles = [
        'landing' => ['title' => 'TerraVerde', 'subtitle' => 'SDG 13 - Climate Action'],
        'login' => ['title' => 'Login', 'subtitle' => 'Welcome back to Climate Tracker'],
        'register' => ['title' => 'Register', 'subtitle' => 'Join the eco-movement'],
        'dashboard' => ['title' => 'Daily Dashboard', 'subtitle' => "Today's climate challenges"],
        'challenges' => ['title' => 'Challenges', 'subtitle' => 'Browse & filter all challenges'],
        'carbon' => ['title' => 'Carbon Tracker', 'subtitle' => 'Your environmental impact'],
        'leaderboard' => ['title' => 'Leaderboard', 'subtitle' => 'Top eco-warriors'],
        'feed' => ['title' => 'Activity Feed', 'subtitle' => 'What the community is doing'],
        'map' => ['title' => 'Community Map', 'subtitle' => 'Eco events near you'],
        'profile' => ['title' => 'My Profile', 'subtitle' => 'Your eco journey'],
        'badges' => ['title' => 'Badges & Achievements', 'subtitle' => 'Unlock your rewards'],
        'stats' => ['title' => 'Personal Stats', 'subtitle' => 'Your impact over time'],
        'rewards' => ['title' => 'Rewards', 'subtitle' => 'Redeem your points'],
        'learn' => ['title' => 'Learn', 'subtitle' => 'Climate action education'],
        'referral' => ['title' => 'Refer Friends', 'subtitle' => 'Grow the eco community'],
        'notifications' => ['title' => 'Notifications', 'subtitle' => 'Stay up to date'],
        'admin' => ['title' => 'Admin Panel', 'subtitle' => 'Platform management'],
        'verify' => ['title' => 'Verification', 'subtitle' => 'Submit proof of action'],
    ];

    $currentRoute = Route::currentRouteName() ?? 'dashboard';
    $page = $pageTitles[$currentRoute] ?? ['title' => 'TerraVerde', 'subtitle' => ''];
    $unread = 3;
@endphp

<header class="sticky top-0 z-30 bg-white/95 backdrop-blur-sm border-b border-gray-100 px-4 lg:px-6 h-16 flex items-center gap-4">
    <!-- Mobile menu / Logo -->
    <div class="flex items-center gap-3">
        @auth
            <button onclick="toggleSidebar()" class="lg:hidden p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-xl transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
            </button>
        @endauth
        
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
            <div class="w-10 h-10 flex items-center justify-center transition-transform group-hover:scale-105 duration-300">
                <img src="{{ asset('image/icon-terraverde.png') }}" alt="TerraVerde Logo" class="w-full h-full object-contain drop-shadow-sm">
            </div>
            <span class="text-base font-black text-gray-900 tracking-tight hidden xs:block">TerraVerde</span> 
        </a>
    </div>

    <!-- Page title -->
    <div class="flex-1 min-w-0">
        <h2 class="text-sm font-bold text-gray-900 leading-tight">{{ $page['title'] }}</h2>
        <p class="text-[10px] text-gray-500 hidden sm:block">{{ $page['subtitle'] }}</p>
    </div>

    <!-- Right actions -->
    <div class="flex items-center gap-2">
        @auth
            <!-- Streak pill -->
            <div class="hidden sm:flex items-center gap-1.5 bg-orange-50 border border-orange-100 rounded-full px-3 py-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="currentColor" class="text-orange-500"><path d="M12 2c0 0-5.5 5-5.5 10.5A5.5 5.5 0 0 0 12 18a5.5 5.5 0 0 0 5.5-5.5C17.5 7 12 2 12 2Zm0 14a3.5 3.5 0 0 1-3.5-3.5C8.5 9.4 12 5.5 12 5.5s3.5 3.9 3.5 7A3.5 3.5 0 0 1 12 16Z"/></svg>
                <span class="text-xs font-bold text-orange-600">{{ $currentUser['streak'] }}</span>
            </div>

            <!-- Points pill -->
            <div class="hidden sm:flex items-center gap-1.5 bg-green-50 border border-green-100 rounded-full px-3 py-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="currentColor" class="text-green-500"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                <span class="text-xs font-bold text-green-700">{{ number_format($currentUser['totalPoints']) }}</span>
            </div>

            <!-- Search toggle -->
            <button class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-xl transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            </button>

            <!-- Notifications -->
            <div class="relative">
                <a href="{{ route('notifications') }}" class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-xl transition-colors block">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                    @if($unread > 0)
                        <span class="absolute top-1 right-1 w-4 h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center animate-notif">
                            {{ $unread }}
                        </span>
                    @endif
                </a>
            </div>

            <!-- Avatar -->
            <a href="{{ route('profile') }}">
                <img src="{{ $currentUser['avatar'] }}" alt="{{ $currentUser['name'] }}" class="w-8 h-8 rounded-full object-cover ring-2 ring-green-200 hover:ring-green-400 transition-all cursor-pointer" />
            </a>
        @else
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="text-xs font-bold text-gray-600 hover:text-green-600 transition-colors">Login</a>
                <a href="{{ route('register') }}" class="px-4 py-2 bg-green-600 text-white text-xs font-black rounded-xl shadow-sm hover:bg-green-700 transition-all active:scale-95">Register</a>
            </div>
        @endauth
    </div>
</header>
