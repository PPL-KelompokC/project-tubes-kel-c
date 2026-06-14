@extends('layouts.app')

@section('title', 'Refer Friends - TerraVerde')

@section('content')
@php
    $user = Auth::user();
    // Generate a deterministic but random-looking referral code for the user if they don't have one
    if (!$user->referral_code) {
        $firstName = strtoupper(preg_replace('/[^a-zA-Z]/', '', explode(' ', $user->name)[0] ?? 'ECO'));
        if (strlen($firstName) < 3) {
            $firstName = str_pad($firstName, 3, 'X');
        }
        do {
            $newCode = $firstName . rand(1000, 9999);
        } while (\App\Models\User::where('referral_code', $newCode)->exists());
        
        $user->referral_code = $newCode;
        $user->save();
    }
    $referralCode = $user->referral_code;

    // Fetch actual referred users from database
    $referrals = $user->referrals()->latest()->get();
    $referralsCount = $referrals->count();
    $totalEarned = $referralsCount * 75;
@endphp

<div x-data="referralApp()" class="p-4 lg:p-6 max-w-4xl mx-auto space-y-6">
    <!-- Hero Banner -->
    <div class="rounded-3xl p-6 bg-gradient-to-r from-emerald-50 via-teal-50 to-sky-50 border border-green-100 shadow-sm relative overflow-hidden animate-bounce-in">
        <!-- Decorative subtle overlay pattern -->
        <div class="absolute inset-0 opacity-40 bg-[radial-gradient(circle_at_30%_107%,#d1fae5_0%,transparent_50%),radial-gradient(circle_at_80%_20%,#e0f2fe_0%,transparent_50%)]"></div>
        
        <div class="relative z-10 flex flex-col sm:flex-row items-center gap-5">
            <div class="w-16 h-16 bg-amber-100 border border-amber-200 rounded-2xl flex items-center justify-center text-3xl flex-shrink-0 animate-float shadow-sm">
                🤝
            </div>
            <div class="text-center sm:text-left flex-1">
                <h1 class="text-2xl font-black text-gray-800 tracking-tight">Invite Friends, Save the Planet!</h1>
                <p class="text-gray-600 text-sm mt-1">
                    Earn <strong class="text-emerald-700 font-extrabold">75 points</strong> for every friend who joins using your code. They get <strong class="text-emerald-700 font-extrabold">50 bonus points</strong> too!
                </p>
                <div class="mt-4 flex flex-wrap justify-center sm:justify-start gap-3">
                    <div class="bg-white/80 backdrop-blur-xs border border-emerald-100 rounded-2xl px-4 py-2 flex items-center shadow-xs">
                        <span class="text-xs text-gray-500 font-medium font-semibold">Your referrals</span>
                        <span class="ml-2 font-black text-emerald-700 text-sm">{{ $referralsCount }}</span>
                    </div>
                    <div class="bg-white/80 backdrop-blur-xs border border-emerald-100 rounded-2xl px-4 py-2 flex items-center shadow-xs">
                        <span class="text-xs text-gray-500 font-medium font-semibold">Points earned</span>
                        <span class="ml-2 font-black text-emerald-700 text-sm">{{ $totalEarned }} pts</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Referral Code & Link Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Referral Code -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 animate-count-in" style="animation-delay: 0.1s">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Your Referral Code</h3>
            <div class="flex items-center justify-between gap-3 bg-emerald-50/50 border border-emerald-100 rounded-2xl px-5 py-4">
                <span class="text-2xl font-black text-emerald-700 tracking-widest font-mono select-all" x-text="referralCode"></span>
                <button @click="copyCode()" class="p-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl shadow-sm transition-all active:scale-95 duration-150 flex-shrink-0 cursor-pointer" title="Copy Referral Code">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect width="14" height="14" x="8" y="8" rx="2" ry="2"/>
                        <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/>
                    </svg>
                </button>
            </div>
            <p class="text-[11px] text-gray-400 mt-3">Share this code or send the link below</p>
        </div>

        <!-- Referral Link -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 animate-count-in" style="animation-delay: 0.2s">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Referral Link</h3>
            <div class="flex items-center justify-between gap-3 bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3.5">
                <span class="text-xs text-gray-600 truncate font-semibold flex-1 select-all" x-text="referralLink"></span>
                <button @click="copyLink()" class="p-2.5 bg-slate-700 hover:bg-slate-800 text-white rounded-xl shadow-sm transition-all active:scale-95 duration-150 flex-shrink-0 cursor-pointer" title="Copy Referral Link">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect width="14" height="14" x="8" y="8" rx="2" ry="2"/>
                        <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/>
                    </svg>
                </button>
            </div>
            <p class="text-[11px] text-gray-400 mt-3">Copy and share anywhere</p>
        </div>
    </div>

    <!-- Share Via Section -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 animate-count-in" style="animation-delay: 0.3s">
        <h3 class="flex items-center gap-2 text-sm font-bold text-gray-800 mb-4">
            <span>📢</span> Share Via
        </h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <!-- Twitter / X -->
            <button @click="shareTwitter()" class="flex flex-col items-center gap-2.5 p-4 rounded-2xl border border-gray-100 bg-white hover:bg-sky-50 hover:border-sky-100 hover:text-sky-600 transition-all duration-200 cursor-pointer group">
                <div class="p-3 rounded-xl bg-gray-50 group-hover:bg-sky-100/50 transition-colors">
                    <svg class="w-6 h-6 text-gray-800 group-hover:text-sky-600 transition-colors" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                    </svg>
                </div>
                <span class="text-xs font-bold text-gray-700 group-hover:text-sky-700 transition-colors">Twitter / X</span>
            </button>

            <!-- WhatsApp -->
            <button @click="shareWhatsApp()" class="flex flex-col items-center gap-2.5 p-4 rounded-2xl border border-gray-100 bg-white hover:bg-green-50 hover:border-green-100 hover:text-green-600 transition-all duration-200 cursor-pointer group">
                <div class="p-3 rounded-xl bg-gray-50 group-hover:bg-green-100/50 transition-colors">
                    <svg class="w-6 h-6 text-gray-800 group-hover:text-green-600 transition-colors" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.457L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.37 9.864-9.799.002-2.63-1.023-5.101-2.885-6.965C16.528 2.012 14.075.99 11.457.99c-5.436 0-9.861 4.37-9.865 9.8.001 1.636.452 3.237 1.31 4.646L1.87 20.893l5.094-1.336z"/>
                    </svg>
                </div>
                <span class="text-xs font-bold text-gray-700 group-hover:text-green-700 transition-colors">WhatsApp</span>
            </button>

            <!-- Email -->
            <button @click="shareEmail()" class="flex flex-col items-center gap-2.5 p-4 rounded-2xl border border-gray-100 bg-white hover:bg-red-50 hover:border-red-100 hover:text-red-600 transition-all duration-200 cursor-pointer group">
                <div class="p-3 rounded-xl bg-gray-50 group-hover:bg-red-100/50 transition-colors">
                    <svg class="w-6 h-6 text-gray-800 group-hover:text-red-600 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect width="20" height="16" x="2" y="4" rx="2"/>
                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                    </svg>
                </div>
                <span class="text-xs font-bold text-gray-700 group-hover:text-red-700 transition-colors">Email</span>
            </button>

            <!-- More... -->
            <button @click="isModalOpen = true" class="flex flex-col items-center gap-2.5 p-4 rounded-2xl border border-gray-100 bg-white hover:bg-purple-50 hover:border-purple-100 hover:text-purple-600 transition-all duration-200 cursor-pointer group">
                <div class="p-3 rounded-xl bg-gray-50 group-hover:bg-purple-100/50 transition-colors">
                    <svg class="w-6 h-6 text-gray-800 group-hover:text-purple-600 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="18" cy="5" r="3"/>
                        <circle cx="6" cy="12" r="3"/>
                        <circle cx="18" cy="19" r="3"/>
                        <line x1="8.59" x2="15.42" y1="13.51" y2="17.49"/>
                        <line x1="15.41" x2="8.59" y1="6.51" y2="10.49"/>
                    </svg>
                </div>
                <span class="text-xs font-bold text-gray-700 group-hover:text-purple-700 transition-colors">More...</span>
            </button>
        </div>
    </div>

    <!-- How It Works Section -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 animate-count-in" style="animation-delay: 0.4s">
        <h3 class="flex items-center gap-2 text-sm font-bold text-gray-800 mb-6">
            <span>🌱</span> How It Works
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative">
            <!-- Connector line on desktop -->
            <div class="hidden md:block absolute top-4 left-[15%] right-[15%] h-[2px] bg-emerald-50 z-0"></div>
            
            <!-- Step 1 -->
            <div class="flex items-start gap-4 relative z-10">
                <div class="w-8 h-8 rounded-full bg-emerald-600 text-white text-sm font-black flex items-center justify-center flex-shrink-0 shadow-sm ring-4 ring-emerald-50">
                    1
                </div>
                <div>
                    <div class="flex items-center gap-1.5 font-bold text-gray-800 text-sm">
                        <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
                        </svg>
                        <p>Share Your Code</p>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 pl-0.5">Send your unique referral code or link to friends</p>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="flex items-start gap-4 relative z-10">
                <div class="w-8 h-8 rounded-full bg-emerald-600 text-white text-sm font-black flex items-center justify-center flex-shrink-0 shadow-sm ring-4 ring-emerald-50">
                    2
                </div>
                <div>
                    <div class="flex items-center gap-1.5 font-bold text-gray-800 text-sm">
                        <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                        <p>Friend Joins</p>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 pl-0.5">Your friend signs up and enters your referral code</p>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="flex items-start gap-4 relative z-10">
                <div class="w-8 h-8 rounded-full bg-emerald-600 text-white text-sm font-black flex items-center justify-center flex-shrink-0 shadow-sm ring-4 ring-emerald-50">
                    3
                </div>
                <div>
                    <div class="flex items-center gap-1.5 font-bold text-gray-800 text-sm">
                        <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <p>Both Earn Points</p>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 pl-0.5">You get 75 pts, they get 50 bonus pts to start!</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Your Referrals List Section -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden animate-count-in" style="animation-delay: 0.5s">
        <div class="px-6 py-5 border-b border-gray-50 flex items-center justify-between">
            <h3 class="text-sm font-bold text-gray-800">Your Referrals</h3>
            <span class="text-xs bg-emerald-100 text-emerald-700 font-extrabold px-3 py-1 rounded-full">{{ $referralsCount }} active</span>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($referrals as $ref)
                <div class="flex items-center justify-between px-6 py-4 hover:bg-gray-50/50 transition-colors duration-150">
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-400 to-teal-500 flex items-center justify-center text-white text-sm font-black shadow-xs">
                            {{ substr($ref->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ $ref->name }}</p>
                            <p class="text-[11px] text-gray-400 mt-0.5">
                                Joined {{ $ref->created_at->format('M d, Y') }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1 bg-amber-50 border border-amber-100 rounded-xl px-2 py-1">
                            <svg class="w-3.5 h-3.5 text-amber-500 fill-amber-500" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            <span class="text-xs font-black text-amber-700">+75</span>
                        </div>
                        <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center">
                    <div class="text-4xl mb-2">🌱</div>
                    <p class="text-sm font-bold text-gray-500">No referrals yet</p>
                    <p class="text-xs text-gray-400 mt-1">Invite friends to join and earn 75 points for each!</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- More Shares Modal (Bottom Drawer/Dialog) -->
    <div x-show="isModalOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4"
         @click="isModalOpen = false"
         style="display: none;">
        
        <div x-show="isModalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-8 scale-95"
             class="bg-white rounded-3xl w-full max-w-sm shadow-2xl overflow-hidden border border-gray-100"
             @click.stop>
            
            <!-- Modal Header -->
            <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-800">Share Invite Link</h3>
                <button @click="isModalOpen = false" class="p-1.5 hover:bg-gray-50 text-gray-400 hover:text-gray-600 rounded-xl transition-all cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M18 6L6 18M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Modal Grid -->
            <div class="p-5 grid grid-cols-3 gap-3">
                <!-- Facebook -->
                <button @click="shareFacebook(); isModalOpen = false;" class="flex flex-col items-center gap-2 p-3 rounded-2xl border border-gray-100 bg-white hover:bg-blue-50/50 hover:border-blue-100 hover:scale-[1.03] active:scale-95 transition-all duration-200 cursor-pointer group">
                    <svg class="w-7 h-7 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    <span class="text-[10px] font-bold text-gray-700 font-semibold">Facebook</span>
                </button>

                <!-- Telegram -->
                <button @click="shareTelegram(); isModalOpen = false;" class="flex flex-col items-center gap-2 p-3 rounded-2xl border border-gray-100 bg-white hover:bg-sky-50/50 hover:border-sky-100 hover:scale-[1.03] active:scale-95 transition-all duration-200 cursor-pointer group">
                    <svg class="w-7 h-7 text-sky-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-1-.65-.35-1 .22-1.62.15-.16 2.72-2.5 2.77-2.7.01-.03.01-.14-.05-.2-.06-.06-.16-.04-.23-.02-.1.02-1.69 1.07-4.78 3.16-.45.31-.86.47-1.22.46-.4-.01-1.17-.22-1.74-.41-.7-.23-1.26-.35-1.21-.75.02-.2.29-.41.8-.62 3.14-1.37 5.24-2.28 6.28-2.73 2.98-1.28 3.6-1.5 4.01-1.5.09 0 .29.02.42.12.11.09.14.21.15.3-.01.07 0 .17-.01.24z"/>
                    </svg>
                    <span class="text-[10px] font-bold text-gray-700 font-semibold">Telegram</span>
                </button>

                <!-- LinkedIn -->
                <button @click="shareLinkedIn(); isModalOpen = false;" class="flex flex-col items-center gap-2 p-3 rounded-2xl border border-gray-100 bg-white hover:bg-blue-50/50 hover:border-blue-100 hover:scale-[1.03] active:scale-95 transition-all duration-200 cursor-pointer group">
                    <svg class="w-7 h-7 text-blue-700" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                    </svg>
                    <span class="text-[10px] font-bold text-gray-700 font-semibold">LinkedIn</span>
                </button>

                <!-- Instagram -->
                <button @click="shareInstagram(); isModalOpen = false;" class="flex flex-col items-center gap-2 p-3 rounded-2xl border border-gray-100 bg-white hover:bg-pink-50/50 hover:border-pink-100 hover:scale-[1.03] active:scale-95 transition-all duration-200 cursor-pointer group">
                    <svg class="w-7 h-7 text-pink-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/>
                    </svg>
                    <span class="text-[10px] font-bold text-gray-700 font-semibold">Instagram</span>
                </button>

                <!-- TikTok -->
                <button @click="shareTikTok(); isModalOpen = false;" class="flex flex-col items-center gap-2 p-3 rounded-2xl border border-gray-100 bg-white hover:bg-slate-50 hover:border-slate-200 hover:scale-[1.03] active:scale-95 transition-all duration-200 cursor-pointer group">
                    <svg class="w-7 h-7 text-black" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.17-2.86-.74-3.99-1.72-.08-.07-.15-.15-.22-.23v6.86c-.05 2.12-.83 4.22-2.39 5.67-1.57 1.47-3.79 2.19-5.91 1.95-2.09-.23-4.07-1.39-5.18-3.2-1.23-1.97-1.39-4.57-.42-6.68.96-2.12 3.12-3.66 5.43-3.87.52-.05 1.05-.04 1.57.02V12.1c-.81-.07-1.65.11-2.34.56-.78.5-1.34 1.35-1.5 2.26-.23 1.26.26 2.61 1.25 3.39.98.79 2.37.91 3.47.31.98-.52 1.6-1.56 1.67-2.68V.02z"/>
                    </svg>
                    <span class="text-[10px] font-bold text-gray-700 font-semibold">TikTok</span>
                </button>

                <!-- Copy Link (Generic) -->
                <button @click="copyLink(); isModalOpen = false;" class="flex flex-col items-center gap-2 p-3 rounded-2xl border border-gray-100 bg-white hover:bg-amber-50/50 hover:border-amber-100 hover:scale-[1.03] active:scale-95 transition-all duration-200 cursor-pointer group">
                    <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect width="14" height="14" x="8" y="8" rx="2" ry="2"/>
                        <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/>
                    </svg>
                    <span class="text-[10px] font-bold text-gray-700 font-semibold">Copy Link</span>
                </button>

                <!-- Native Share (Conditionally shown if browser supports it) -->
                <template x-if="canNativeShare">
                    <button @click="nativeShare(); isModalOpen = false;" class="flex flex-col items-center gap-2 p-3 rounded-2xl border border-gray-100 bg-white hover:bg-emerald-50/50 hover:border-emerald-100 hover:scale-[1.03] active:scale-95 transition-all duration-200 cursor-pointer group col-span-3 mt-1.5">
                        <div class="flex items-center gap-2 text-emerald-600 font-bold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8M16 6l-4-4-4 4M12 2v13"/>
                            </svg>
                            <span class="text-[11px] font-semibold">Use System Share Sheet</span>
                        </div>
                    </button>
                </template>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function referralApp() {
        return {
            referralCode: '{{ $referralCode }}',
            referralLink: '',
            isModalOpen: false,
            canNativeShare: !!navigator.share,
            
            init() {
                this.referralLink = window.location.origin + '/register?ref=' + this.referralCode;
            },
            
            copyCode() {
                navigator.clipboard.writeText(this.referralCode).then(() => {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Referral code copied!',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                });
            },
            
            copyLink() {
                navigator.clipboard.writeText(this.referralLink).then(() => {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Referral link copied!',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                });
            },
            
            shareTwitter() {
                const text = `Join me on TerraVerde to save the planet! Use my referral code: ${this.referralCode}`;
                const url = `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(this.referralLink)}`;
                window.open(url, '_blank');
            },
            
            shareWhatsApp() {
                const text = `Join me on TerraVerde to save the planet! Use my referral link: ${this.referralLink}`;
                const url = `https://api.whatsapp.com/send?text=${encodeURIComponent(text)}`;
                window.open(url, '_blank');
            },
            
            shareEmail() {
                const subject = 'Join me on TerraVerde!';
                const body = `Hi! Join me on TerraVerde to take climate action together. Sign up using my referral link: ${this.referralLink}`;
                const url = `mailto:?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
                window.location.href = url;
            },
            
            shareFacebook() {
                const url = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(this.referralLink)}`;
                window.open(url, '_blank');
            },
            
            shareTelegram() {
                const text = `Join me on TerraVerde to save the planet!`;
                const url = `https://t.me/share/url?url=${encodeURIComponent(this.referralLink)}&text=${encodeURIComponent(text)}`;
                window.open(url, '_blank');
            },
            
            shareLinkedIn() {
                const url = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(this.referralLink)}`;
                window.open(url, '_blank');
            },
            
            shareInstagram() {
                navigator.clipboard.writeText(this.referralLink).then(() => {
                    Swal.fire({
                        icon: 'info',
                        title: 'Link Copied!',
                        text: 'Referral link copied to clipboard. Paste it in your Instagram bio or DMs!',
                        confirmButtonText: 'Go to Instagram',
                        confirmButtonColor: '#10B981',
                        showCancelButton: true,
                        cancelButtonText: 'Cancel',
                        customClass: {
                            confirmButton: 'rounded-2xl px-4 py-2 text-sm font-semibold',
                            cancelButton: 'rounded-2xl px-4 py-2 text-sm font-semibold'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.open('https://instagram.com', '_blank');
                        }
                    });
                });
            },
            
            shareTikTok() {
                navigator.clipboard.writeText(this.referralLink).then(() => {
                    Swal.fire({
                        icon: 'info',
                        title: 'Link Copied!',
                        text: 'Referral link copied to clipboard. Paste it in your TikTok bio or video description!',
                        confirmButtonText: 'Go to TikTok',
                        confirmButtonColor: '#10B981',
                        showCancelButton: true,
                        cancelButtonText: 'Cancel',
                        customClass: {
                            confirmButton: 'rounded-2xl px-4 py-2 text-sm font-semibold',
                            cancelButton: 'rounded-2xl px-4 py-2 text-sm font-semibold'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.open('https://tiktok.com', '_blank');
                        }
                    });
                });
            },
            
            nativeShare() {
                if (navigator.share) {
                    navigator.share({
                        title: 'Join TerraVerde!',
                        text: 'Join me on TerraVerde to save the planet and earn points!',
                        url: this.referralLink
                    }).catch((err) => console.log('Error sharing:', err));
                }
            }
        };
    }
</script>
@endpush
@endsection
