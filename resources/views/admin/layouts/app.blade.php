<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siklim Admin — @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Inter', sans-serif; -webkit-font-smoothing: antialiased; }
        .sidebar-active { background: #f0fdf4; color: #15803d; border-right: 2px solid #16a34a; }
        .card-shadow { box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px 0 rgba(0, 0, 0, 0.03); }
        .transition-200 { transition: all 200ms cubic-bezier(0.4, 0, 0.2, 1); }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-900" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden" x-show="sidebarOpen" @click="sidebarOpen = false"></div>
        <aside class="fixed inset-y-0 left-0 w-72 bg-white border-r border-slate-200 flex flex-col z-50 transform lg:transform-none transition-transform duration-300"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            @include('admin.layouts.partials.sidebar')
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden relative lg:ml-72">
            <!-- Top Navbar -->
            <header class="h-16 bg-white/80 backdrop-blur-md border-b border-slate-200 flex items-center justify-between px-4 sm:px-8 sticky top-0 z-30">
                <div class="flex items-center gap-4">
                    <button class="lg:hidden text-slate-500 hover:text-emerald-600" @click.stop="sidebarOpen = !sidebarOpen">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div class="hidden sm:block relative max-w-md w-full">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input type="text" placeholder="Search anything..." class="w-full pl-10 pr-4 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-200">
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <button class="text-slate-500 hover:text-emerald-600 transition-200 relative">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                    </button>
                    
                    <div class="flex items-center gap-3 pl-6 border-l border-slate-200">
                        <div class="text-right">
                            <p class="text-xs font-bold text-slate-800">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] font-medium text-slate-400 uppercase tracking-wider">Administrator</p>
                        </div>
                        <div class="w-9 h-9 rounded-full bg-emerald-100 border border-emerald-200 flex items-center justify-center text-emerald-700 font-bold text-sm">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Scrollable Content -->
            <main class="flex-1 overflow-y-auto p-8">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-100 flex items-center gap-3 text-sm animate-in fade-in slide-in-from-top-4 duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 p-4 bg-rose-50 text-rose-700 rounded-xl border border-rose-100 animate-in fade-in slide-in-from-top-4 duration-300">
                        <div class="flex items-center gap-3 text-sm font-bold mb-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                            Whoops! Please check the following errors:
                        </div>
                        <ul class="list-disc list-inside text-xs space-y-1 ml-8">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <div class="mb-8">
                    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">@yield('page_title')</h1>
                    <p class="text-slate-500 text-sm mt-1">@yield('page_subtitle', 'Manage your eco-system and platform metrics.')</p>
                </div>

                @yield('content')
            </main>
        </div>
    </div>
    @yield('scripts')
</body>
</html>
