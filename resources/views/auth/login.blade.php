@extends('layouts.app')

@section('title', 'Sign In — Siklim Admin')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-[#f8fafc] p-6">
    <div class="w-full max-w-[440px] space-y-8">
        <!-- Logo -->
        <div class="text-center">
            <div class="inline-flex items-center justify-center mb-6">
                <img src="{{ asset('image/icon-siklim.png') }}" alt="Siklim Logo" class="w-16 h-16 object-contain drop-shadow-xl">
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Welcome back</h1>
            <p class="text-slate-500 text-sm mt-2 font-medium">Please enter your details to sign in to your account.</p>
        </div>

        <!-- Card -->
        <div class="bg-white p-10 rounded-[2.5rem] border border-slate-200 shadow-2xl shadow-slate-200/50">
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-2xl border border-emerald-100 flex items-center gap-3 text-xs font-bold">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Email Address</label>
                    <input type="email" name="email" placeholder="admin@siklim.com" required
                        class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all placeholder:text-slate-300">
                    @error('email') <p class="text-[10px] font-bold text-rose-500 mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between px-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Password</label>
                        <a href="#" class="text-[10px] font-black text-emerald-600 hover:text-emerald-700 uppercase tracking-widest">Forgot?</a>
                    </div>
                    <input type="password" name="password" placeholder="••••••••" required
                        class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all placeholder:text-slate-300">
                    @error('password') <p class="text-[10px] font-bold text-rose-500 mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-3 px-1">
                    <input type="checkbox" name="remember" id="remember" class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 transition-all cursor-pointer">
                    <label for="remember" class="text-xs font-bold text-slate-500 cursor-pointer select-none">Remember this device</label>
                </div>

                <button type="submit" class="w-full py-4 bg-emerald-600 text-white rounded-2xl text-sm font-black hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-100 active:scale-[0.98]">
                    Sign In
                </button>
            </form>

            <div class="mt-10 pt-8 border-t border-slate-100 text-center">
                <p class="text-xs font-bold text-slate-400">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="text-emerald-600 hover:underline">Create one for free</a>
                </p>
            </div>
        </div>

        <p class="text-center text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">
            &copy; 2026 Siklim Eco Platform
        </p>
    </div>
</div>
@endsection
