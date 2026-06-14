@extends('layouts.app')

@section('title', 'Rewards - TerraVerde')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
@php
    $points = $balanceData['points'];
    $activeTab = request('tab', 'redeem');
@endphp

<div class="p-4 lg:p-6 max-w-5xl mx-auto space-y-5" x-data="rewardSystem()">
    <!-- Points balance card -->
    <div class="rounded-3xl p-6 text-white relative overflow-hidden animate-bounce-in shadow-lg" style="background: linear-gradient(135deg, #15803d 0%, #047857 45%, #0369a1 100%);">
        <!-- Decorative overlay circles -->
        <div class="absolute inset-0 rounded-3xl" style="background-image: radial-gradient(circle at 15% 75%, rgba(52,211,153,0.18) 0%, transparent 55%), radial-gradient(circle at 85% 15%, rgba(56,189,248,0.15) 0%, transparent 55%);"></div>
        <div class="relative z-10 flex items-center justify-between gap-4">
            <div>
                <p class="text-green-200 text-sm font-medium">Your Balance</p>
                <div class="flex items-end gap-2 mt-1">
                    <span class="text-5xl font-black" x-text="numberFormat(points)">{{ number_format($points) }}</span>
                    <span class="text-lg font-semibold text-green-200 mb-1">pts</span>
                </div>
                <p class="text-green-100 text-xs mt-1">Earned through eco actions</p>
            </div>
            <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center animate-float">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"><polyline points="20 12 20 22 4 22 4 12"/><rect width="22" height="5" x="1" y="7"/><line x1="12" x2="12" y1="22" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
            </div>
        </div>

        <!-- Quick stats -->
        <div class="mt-4 grid grid-cols-3 gap-3">
            <div class="bg-white/15 backdrop-blur-sm rounded-xl p-2.5 text-center">
                <p class="text-white font-bold text-sm">{{ number_format($balanceData['earned_total']) }}</p>
                <p class="text-green-200 text-[10px]">Earned (total)</p>
            </div>
            <div class="bg-white/15 backdrop-blur-sm rounded-xl p-2.5 text-center">
                <p class="text-white font-bold text-sm">{{ number_format($balanceData['redeemed_total']) }}</p>
                <p class="text-green-200 text-[10px]">Redeemed</p>
            </div>
            <div class="bg-white/15 backdrop-blur-sm rounded-xl p-2.5 text-center">
                <p class="text-white font-bold text-sm">+{{ number_format($balanceData['this_month_earned']) }}</p>
                <p class="text-green-200 text-[10px]">This month</p>
            </div>
        </div>
        <div class="absolute -right-6 -bottom-6 w-32 h-32 rounded-full bg-white/5"></div>
    </div>

    <!-- Tabs -->
    <div class="flex gap-1 bg-gray-100 p-1 rounded-xl">
        <a href="{{ route('rewards', ['tab' => 'redeem']) }}" class="flex-1 py-2.5 rounded-lg text-sm font-semibold text-center transition-all {{ $activeTab === 'redeem' ? 'bg-white text-green-700 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
            Redeem Points
        </a>
        <a href="{{ route('rewards', ['tab' => 'history']) }}" class="flex-1 py-2.5 rounded-lg text-sm font-semibold text-center transition-all {{ $activeTab === 'history' ? 'bg-white text-green-700 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
            Transaction History
        </a>
    </div>

    @if($activeTab === 'redeem')
        <div>
            <p class="text-sm text-gray-500 mb-4">Exchange your points for real-world eco impact</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($rewardItems as $i => $item)
                    @php 
                        $canAfford = $points >= $item->points_required; 
                        $isComingSoon = $item->status === 'coming_soon';
                    @endphp
                    <div class="bg-white rounded-2xl border shadow-sm p-4 transition-all card-hover animate-count-in {{ !$isComingSoon ? 'border-gray-100' : 'border-gray-100 opacity-60' }}" style="animation-delay: {{ $i * 0.08 }}s">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-3 mx-auto 
                            @if($item->category == 'physical') text-purple-600 bg-purple-50 
                            @elseif($item->category == 'digital') text-emerald-600 bg-emerald-50 
                            @else text-green-600 bg-green-50 @endif">
                            @if($item->image)
                                <img src="{{ Storage::url($item->image) }}" class="w-full h-full object-cover rounded-2xl">
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    @if($item->category == 'physical')
                                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" x2="21" y1="6" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>
                                    @elseif($item->category == 'digital')
                                        <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>
                                    @else
                                        <path d="M17 14c.83-1.071 1.5-2.547 1.5-4.5C18.5 5.686 15.314 3 12 3S5.5 5.686 5.5 9.5c0 1.953.67 3.429 1.5 4.5"/><path d="M12 3v11"/><path d="M9 21h6"/><path d="M12 16v5"/>
                                    @endif
                                </svg>
                            @endif
                        </div>
                        <h3 class="text-sm font-bold text-gray-900 text-center mb-1">{{ $item->name }}</h3>
                        <p class="text-xs text-gray-500 text-center line-clamp-2 mb-3">{{ $item->description }}</p>

                        <div class="flex items-center justify-center gap-1.5 mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="currentColor" class="text-yellow-500"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            <span class="text-base font-black text-gray-800">{{ number_format($item->points_required) }}</span>
                            <span class="text-sm text-gray-500">points</span>
                        </div>

                        @if($isComingSoon)
                            <div class="text-center py-2.5 text-xs text-gray-400 font-bold bg-gray-50 rounded-xl border border-gray-100">Coming Soon</div>
                        @else
                            <button
                                @click="confirmRedeem({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->points_required }})"
                                :disabled="points < {{ $item->points_required }}"
                                class="w-full py-2.5 rounded-xl text-sm font-bold transition-all duration-200 {{ $canAfford ? 'bg-green-600 text-white shadow-lg shadow-green-100' : 'bg-gray-50 text-gray-400' }}"
                                :class="points >= {{ $item->points_required }} ? 'bg-green-600 hover:bg-green-700 text-white active:scale-95 shadow-lg shadow-green-100' : 'bg-gray-50 text-gray-400 cursor-not-allowed'"
                            >
                                <span x-text="points >= {{ $item->points_required }} ? 'Redeem Now' : 'Need ' + numberFormat({{ $item->points_required }} - points) + ' pts'">
                                    {{ $canAfford ? 'Redeem Now' : 'Need ' . number_format($item->points_required - $points) . ' pts' }}
                                </span>
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden animate-count-in">
            <div class="divide-y divide-gray-50">
                @foreach($fullHistory as $i => $tx)
                    <div class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors animate-count-in" style="animation-delay: {{ $i * 0.04 }}s">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 {{ $tx['type'] === 'earn' ? 'bg-green-100' : 'bg-orange-100' }}">
                            @if($tx['type'] === 'earn')
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600"><line x1="7" x2="17" y1="17" y2="7"/><polyline points="7 7 17 7 17 17"/></svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-orange-600"><line x1="17" x2="7" y1="7" y2="17"/><polyline points="17 17 7 17 7 7"/></svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ $tx['description'] }}</p>
                            <div class="flex items-center gap-2">
                                <p class="text-xs text-gray-400">{{ $tx['date'] }}</p>
                                @if($tx['type'] === 'redeem')
                                    <span class="text-[10px] px-1.5 py-0.5 rounded-full font-bold uppercase tracking-wider 
                                        @if($tx['status'] == 'pending') bg-amber-50 text-amber-600 
                                        @elseif($tx['status'] == 'completed') bg-emerald-50 text-emerald-600
                                        @else bg-rose-50 text-rose-600 @endif">
                                        {{ $tx['status'] }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <span class="text-sm font-bold flex-shrink-0 {{ $tx['type'] === 'earn' ? 'text-green-600' : 'text-orange-600' }}">
                            {{ $tx['type'] === 'earn' ? '+' : '-' }}{{ number_format($tx['points']) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Redeem Confirmation Modal (Alpine.js) -->
    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-bold text-gray-900">Confirm Redemption</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Are you sure you want to redeem <span class="font-bold text-gray-900" x-text="selectedReward?.name"></span>?
                            </p>
                            <div class="mt-4 bg-gray-50 rounded-2xl p-4 space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Points required:</span>
                                    <span class="font-bold text-gray-900" x-text="numberFormat(selectedReward?.points_required) + ' pts'"></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Current balance:</span>
                                    <span class="font-bold text-gray-900" x-text="numberFormat(points) + ' pts'"></span>
                                </div>
                                <div class="border-t border-gray-200 pt-2 flex justify-between text-sm">
                                    <span class="text-gray-500 font-medium">Remaining balance:</span>
                                    <span class="font-bold text-green-600" x-text="numberFormat(points - (selectedReward?.points_required || 0)) + ' pts'"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex flex-col sm:flex-row-reverse gap-3">
                    <button @click="processRedeem" :disabled="loading" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2.5 bg-green-600 text-base font-bold text-white hover:bg-green-700 focus:outline-none sm:w-auto sm:text-sm transition-all active:scale-95 disabled:opacity-50">
                        <span x-show="!loading">Confirm Redeem</span>
                        <span x-show="loading" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Processing...
                        </span>
                    </button>
                    <button @click="showModal = false" :disabled="loading" class="w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2.5 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none sm:w-auto sm:text-sm transition-all">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function rewardSystem() {
        return {
            points: @json($points),
            showModal: false,
            selectedReward: null,
            loading: false,

            numberFormat(val) {
                return new Intl.NumberFormat().format(val);
            },

            confirmRedeem(id, name, points_required) {
                this.selectedReward = {
                    id: id,
                    name: name,
                    points_required: points_required
                };
                this.showModal = true;
            },

            async processRedeem() {
                this.loading = true;
                try {
                    const response = await fetch(`/rewards/${this.selectedReward.id}/redeem`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    const data = await response.json();

                    if (response.ok) {
                        this.points = data.new_balance;
                        this.showModal = false;
                        
                        // Show success toast (using sweetalert2 if available or simple alert)
                        if (window.Swal) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: data.message,
                                timer: 3000,
                                showConfirmButton: false,
                                borderRadios: '24px'
                            });
                        } else {
                            alert(data.message);
                        }
                    } else {
                        throw new Error(data.message || 'Something went wrong');
                    }
                } catch (error) {
                    if (window.Swal) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: error.message
                        });
                    } else {
                        alert(error.message);
                    }
                } finally {
                    this.loading = false;
                }
            }
        }
    }
</script>
@endsection
