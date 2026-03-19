@extends('layouts.app')

@section('title', 'Climate Action Challenge Tracker')

@section('content')
<div class="bg-white">
    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg overflow-hidden flex items-center justify-center">
                            <img src="{{ asset('image/icon-siklim.png') }}" alt="Siklim" class="w-full h-full object-contain">
                        </div>
                        <span class="text-xl font-black text-gray-900 tracking-tight">Siklim</span>
                    </div>
                    <div class="hidden sm:ml-10 sm:flex sm:space-x-8">
                        <a href="#home" class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-green-500 text-sm font-medium">Home</a>
                        <a href="#features" class="text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium">Features</a>
                        <a href="#about" class="text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium">About</a>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-bold rounded-xl text-white bg-green-600 hover:bg-green-700 shadow-sm transition-all active:scale-95">
                            Go to Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm font-semibold text-gray-600 hover:text-red-600 transition-colors">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900 transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-bold rounded-xl text-white bg-green-600 hover:bg-green-700 shadow-sm transition-all active:scale-95">
                            Register
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-white pt-16 pb-32">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl tracking-tight font-black text-gray-900 sm:text-5xl md:text-6xl">
                    <span class="block">Take Small Actions,</span>
                    <span class="block text-green-600">Make a Big Impact</span>
                </h1>
                <p class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                    Join thousands of eco-warriors taking daily challenges to reduce carbon footprints and fight climate change. Track your progress, earn badges, and join the community.
                </p>
                <div class="mt-10 flex justify-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-8 py-4 bg-green-600 text-white font-black rounded-2xl shadow-lg shadow-green-100 hover:bg-green-700 hover:shadow-green-200 transition-all active:scale-95">
                            Back to Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="px-8 py-4 bg-green-600 text-white font-black rounded-2xl shadow-lg shadow-green-100 hover:bg-green-700 hover:shadow-green-200 transition-all active:scale-95">
                            Get Started
                        </a>
                        <a href="{{ route('login') }}" class="px-8 py-4 bg-white text-gray-700 border border-gray-100 font-bold rounded-2xl hover:bg-gray-50 transition-all">
                            Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
        
        <!-- Abstract shape -->
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-green-50 rounded-full opacity-50 blur-3xl -z-10"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 bg-blue-50 rounded-full opacity-50 blur-3xl -z-10"></div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-sm font-bold text-green-600 uppercase tracking-widest">Features</h2>
                <p class="mt-2 text-3xl font-black text-gray-900 sm:text-4xl">Everything you need to act</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Daily Challenges</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Fresh eco-friendly tasks every day tailored to your lifestyle.</p>
                </div>
                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="text-orange-500"><path d="M12 2c0 0-5.5 5-5.5 10.5A5.5 5.5 0 0 0 12 18a5.5 5.5 0 0 0 5.5-5.5C17.5 7 12 2 12 2Zm0 14a3.5 3.5 0 0 1-3.5-3.5C8.5 9.4 12 5.5 12 5.5s3.5 3.9 3.5 7A3.5 3.5 0 0 1 12 16Z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Points & Streaks</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Gamify your impact. Earn points and maintain your daily streak.</p>
                </div>
                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-600"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Track Carbon</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Visualize the exact amount of CO2 you've saved for the planet.</p>
                </div>
                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Community</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Connect with local events and other people making a difference.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works -->
    <div class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-black text-gray-900">How It Works</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 relative">
                <!-- Connectors -->
                <div class="hidden md:block absolute top-1/2 left-0 right-0 h-0.5 bg-gray-100 -z-10"></div>
                
                @foreach([
                    ['step' => '1', 'title' => 'Sign up', 'desc' => 'Create your account in seconds and set your location.'],
                    ['step' => '2', 'title' => 'Complete challenges', 'desc' => 'Take action daily and upload proof of your progress.'],
                    ['step' => '3', 'title' => 'Earn rewards', 'desc' => 'Unlock badges and see your rank on the leaderboard.'],
                ] as $item)
                    <div class="text-center bg-white p-6">
                        <div class="w-12 h-12 bg-green-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 font-black text-xl shadow-lg shadow-green-100">
                            {{ $item['step'] }}
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $item['title'] }}</h3>
                        <p class="text-sm text-gray-500">{{ $item['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Gamification Preview -->
    <div class="py-24 bg-gray-50 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <div class="lg:w-1/2">
                    <h2 class="text-3xl font-black text-gray-900 mb-6">Stay Motivated with Gamification</h2>
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                        Our platform uses proven psychological techniques to help you stick to your goals. See your progress through interactive charts and compete with others.
                    </p>
                    <ul class="space-y-4">
                        @foreach(['Unlock rare environmental badges', 'Global and local leaderboards', 'Daily streak monitoring', 'Personal impact dashboard'] as $item)
                            <li class="flex items-center gap-3 font-semibold text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="lg:w-1/2 relative">
                    <!-- Mock UI -->
                    <div class="bg-white rounded-[32px] shadow-2xl p-8 border border-gray-100 animate-bounce-in">
                        <div class="flex items-center justify-between mb-8">
                            <h4 class="font-bold text-gray-900">Your Impact</h4>
                            <span class="bg-orange-100 text-orange-600 px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="currentColor" class="text-orange-500"><path d="M12 2c0 0-5.5 5-5.5 10.5A5.5 5.5 0 0 0 12 18a5.5 5.5 0 0 0 5.5-5.5C17.5 7 12 2 12 2Z"/></svg>
                                23 Day Streak
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-8">
                            <div class="bg-green-50 p-4 rounded-2xl border border-green-100">
                                <p class="text-[10px] text-green-600 font-bold uppercase">Points</p>
                                <p class="text-2xl font-black text-green-700">8,750</p>
                            </div>
                            <div class="bg-blue-50 p-4 rounded-2xl border border-blue-100">
                                <p class="text-[10px] text-blue-600 font-bold uppercase">CO2 Saved</p>
                                <p class="text-2xl font-black text-blue-700">142kg</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <p class="text-xs font-bold text-gray-500 uppercase">Recent Badges</p>
                            <div class="flex gap-3">
                                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="currentColor" class="text-yellow-500"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                </div>
                                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-600"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
                                </div>
                                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-purple-600"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                                </div>
                                <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center opacity-40">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Decorative blobs -->
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-green-200 rounded-full blur-3xl opacity-30 -z-10"></div>
                    <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-blue-200 rounded-full blur-3xl opacity-30 -z-10"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12 border-b border-gray-800 pb-12">
                <div class="col-span-1 md:col-span-1">
                    <div class="flex items-center gap-2.5 mb-6">
                        <div class="w-9 h-9 rounded-lg overflow-hidden flex items-center justify-center flex-shrink-0">
                            <img src="{{ asset('image/icon-siklim.png') }}" alt="Siklim" class="w-full h-full object-contain">
                        </div>
                        <span class="text-xl font-black tracking-tight">Siklim</span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Empowering people to take climate action through gamified daily challenges and community.
                    </p>
                </div>
                <div>
                    <h5 class="font-bold mb-6 text-gray-200 uppercase text-xs tracking-widest">Platform</h5>
                    <ul class="space-y-4 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-green-500 transition-colors">Challenges</a></li>
                        <li><a href="#" class="hover:text-green-500 transition-colors">Leaderboard</a></li>
                        <li><a href="#" class="hover:text-green-500 transition-colors">Carbon Tracking</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-bold mb-6 text-gray-200 uppercase text-xs tracking-widest">Company</h5>
                    <ul class="space-y-4 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-green-500 transition-colors">About Us</a></li>
                        <li><a href="#" class="hover:text-green-500 transition-colors">Sustainability</a></li>
                        <li><a href="#" class="hover:text-green-500 transition-colors">Privacy Policy</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-bold mb-6 text-gray-200 uppercase text-xs tracking-widest">Social</h5>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-xl flex items-center justify-center hover:bg-green-600 transition-all text-gray-400 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-xl flex items-center justify-center hover:bg-green-600 transition-all text-gray-400 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-xl flex items-center justify-center hover:bg-green-600 transition-all text-gray-400 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.27 8.27 0 0 0 4.83 1.54V6.78a4.85 4.85 0 0 1-1.06-.09z"/></svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-gray-500 text-xs">© 2024 Climate Action Tracker. All rights reserved.</p>
                <div class="flex gap-8 text-xs text-gray-500">
                    <span>SDG 13 - Climate Action</span>
                    <span>Indonesia</span>
                </div>
            </div>
        </div>
    </footer>
</div>
@endsection
