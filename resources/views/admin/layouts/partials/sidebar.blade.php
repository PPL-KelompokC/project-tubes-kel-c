<!-- Brand -->
<div class="h-16 flex items-center px-8 border-b border-slate-100">
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
        <img src="{{ asset('image/icon-terraverde.png') }}" alt="TerraVerde Logo" class="w-8 h-8 object-contain">
        <span class="text-lg font-black tracking-tight text-slate-800">TerraVerde<span class="text-emerald-600">.</span></span>
    </a>
</div>

    <!-- Navigation -->
<nav class="flex-1 overflow-y-auto px-4 py-8 space-y-8">
    <!-- HOME -->
    <div>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.15em] mb-4 px-4">HOME</p>
        <div class="space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-200 {{ Request::routeIs('admin.dashboard') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                @include('layouts.partials.icons.layout-dashboard')
                Dashboard
            </a>
        </div>
    </div>

    <!-- CHALLENGES -->
    <div>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.15em] mb-4 px-4">CHALLENGES</p>
        <div class="space-y-1">
            <a href="{{ route('admin.challenges.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-200 {{ Request::routeIs('admin.challenges.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                @include('layouts.partials.icons.list-checks')
                Daily Challenges
            </a>
            <a href="{{ route('admin.submissions.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-200 {{ Request::routeIs('admin.submissions.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                @include('layouts.partials.icons.shield-check')
                Submissions reports
            </a>
        </div>
    </div>

    <!-- COMMUNITY -->
    <div>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.15em] mb-4 px-4">COMMUNITY</p>
        <div class="space-y-1">
            <a href="{{ route('admin.feeds.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-200 {{ Request::routeIs('admin.feeds.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                @include('layouts.partials.icons.activity')
                Activity Feeds
            </a>
            <a href="{{ route('admin.events.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-200 {{ Request::routeIs('admin.events.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                @include('layouts.partials.icons.map')
                Events
            </a>
        </div>
    </div>

    <!-- GAMIFICATION -->
    <div>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.15em] mb-4 px-4">GAMIFICATION</p>
        <div class="space-y-1">
            <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-200 text-slate-500 hover:bg-slate-50 hover:text-slate-900">
                @include('layouts.partials.icons.gift')
                Rewards Management
            </a>
            <a href="{{ route('admin.badges.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-200 {{ Request::routeIs('admin.badges.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                @include('layouts.partials.icons.star')
                Badge Management
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-200 text-slate-500 hover:bg-slate-50 hover:text-slate-900">
                @include('layouts.partials.icons.trophy')
                Leaderboard Control
            </a>
        </div>
    </div>

    <!-- PUBLICATION -->
    <div>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.15em] mb-4 px-4">PUBLICATION</p>
        <div class="space-y-1">
            <a href="{{ route('admin.articles.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-200 {{ Request::routeIs('admin.articles.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                @include('layouts.partials.icons.book-open')
                Learn Management
            </a>
        </div>
    </div>

    <!-- USER MANAGEMENT -->
    <div>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.15em] mb-4 px-4">USER MANAGEMENT</p>
        <div class="space-y-1">
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-200 {{ Request::routeIs('admin.users.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                @include('layouts.partials.icons.leaf')
                Data User
            </a>
        </div>
    </div>
</nav>

    <!-- Sidebar Footer -->
    <div class="p-4 border-t border-slate-100">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-500 hover:bg-red-50 hover:text-red-600 transition-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Sign Out
            </button>
        </form>
    </div>
