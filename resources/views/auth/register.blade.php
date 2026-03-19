@extends('layouts.app')

@section('title', 'Register - Climate Tracker')

@section('content')
<div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-gray-50 eco-pattern">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <a href="/" class="inline-flex items-center gap-2 mb-6 group">
            <div class="w-10 h-10 bg-green-600 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-green-100 group-hover:scale-110 transition-transform">
                🌍
            </div>
            <span class="text-2xl font-black text-gray-900 tracking-tight">Climate Tracker</span>
        </a>
        <h2 class="text-3xl font-black text-gray-900 leading-tight">Join the movement!</h2>
        <p class="mt-2 text-sm text-gray-500 font-medium">Create your account and start your climate journey.</p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-lg px-4">
        <div class="bg-white py-10 px-8 shadow-2xl shadow-gray-200/50 sm:rounded-[32px] border border-gray-100 animate-bounce-in">
            <form class="space-y-6" action="{{ route('register') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-xs font-bold text-gray-700 uppercase tracking-widest mb-2">Full Name</label>
                        <div class="mt-1">
                            <input id="name" name="name" type="text" autocomplete="name" required 
                                class="appearance-none block w-full px-4 py-3.5 border border-gray-100 rounded-2xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-100 focus:border-green-500 sm:text-sm transition-all bg-gray-50/50"
                                placeholder="Maya Johnson">
                        </div>
                        @error('name')
                            <p class="mt-2 text-xs text-red-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="location" class="block text-xs font-bold text-gray-700 uppercase tracking-widest mb-2">Location</label>
                        <div class="mt-1">
                            <input id="location" name="location" type="text" autocomplete="address-level2" required 
                                class="appearance-none block w-full px-4 py-3.5 border border-gray-100 rounded-2xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-100 focus:border-green-500 sm:text-sm transition-all bg-gray-50/50"
                                placeholder="San Francisco, CA">
                        </div>
                        @error('location')
                            <p class="mt-2 text-xs text-red-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-widest mb-2">Email Address</label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required 
                            class="appearance-none block w-full px-4 py-3.5 border border-gray-100 rounded-2xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-100 focus:border-green-500 sm:text-sm transition-all bg-gray-50/50"
                            placeholder="you@example.com">
                    </div>
                    @error('email')
                        <p class="mt-2 text-xs text-red-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-widest mb-2">Password</label>
                        <div class="mt-1">
                            <input id="password" name="password" type="password" autocomplete="new-password" required 
                                class="appearance-none block w-full px-4 py-3.5 border border-gray-100 rounded-2xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-100 focus:border-green-500 sm:text-sm transition-all bg-gray-50/50"
                                placeholder="••••••••">
                        </div>
                        @error('password')
                            <p class="mt-2 text-xs text-red-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-gray-700 uppercase tracking-widest mb-2">Confirm Password</label>
                        <div class="mt-1">
                            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required 
                                class="appearance-none block w-full px-4 py-3.5 border border-gray-100 rounded-2xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-100 focus:border-green-500 sm:text-sm transition-all bg-gray-50/50"
                                placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="terms" name="terms" type="checkbox" required
                            class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded-lg transition-colors cursor-pointer">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="terms" class="text-gray-500 font-medium">I agree to the <a href="#" class="font-bold text-green-600 hover:text-green-700 transition-colors">Terms of Service</a> and <a href="#" class="font-bold text-green-600 hover:text-green-700 transition-colors">Privacy Policy</a>.</label>
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-lg shadow-green-100 text-sm font-black text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all active:scale-95">
                        Create Account
                    </button>
                </div>
            </form>

            <div class="mt-8 pt-8 border-t border-gray-50 text-center">
                <p class="text-sm text-gray-500 font-medium">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="font-black text-green-600 hover:text-green-700 transition-colors">Sign in</a>
                </p>
            </div>
        </div>
        
        <!-- Bottom link -->
        <div class="mt-8 text-center">
            <a href="/" class="text-xs font-bold text-gray-400 hover:text-gray-600 flex items-center justify-center gap-2 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to landing page
            </a>
        </div>
    </div>
</div>
@endsection
