@extends('admin.layouts.app')

@section('title', 'Rewards Management')
@section('page_title', 'Rewards Management')
@section('page_subtitle', 'Manage reward items and track user redemptions.')

@section('content')
<!-- Dashboard Summary -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl border border-slate-200 card-shadow transition-200 hover:border-emerald-200">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total Points Redeemed</p>
                <p class="text-2xl font-bold text-slate-900">{{ number_format($stats['total_redeemed_points']) }} pts</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-200 card-shadow transition-200 hover:border-emerald-200">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z"/></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Most Popular Reward</p>
                <p class="text-xl font-bold text-slate-900">{{ $stats['popular_reward'] ? $stats['popular_reward']->name : 'N/A' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-200 card-shadow transition-200 hover:border-emerald-200">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center text-orange-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Transactions This Month</p>
                <p class="text-2xl font-bold text-slate-900">{{ $stats['monthly_transactions'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Main Section -->
<div class="bg-white rounded-2xl border border-slate-200 card-shadow overflow-hidden">
    <!-- Table Header/Filters -->
    <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-4 w-full sm:w-auto">
            <form action="{{ route('admin.rewards.index') }}" method="GET" class="flex items-center gap-3 w-full sm:w-auto">
                <div class="relative flex-1 sm:w-64">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search rewards..." class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-200">
                </div>
                <select name="status" onchange="this.form.submit()" class="pl-4 pr-10 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-200 appearance-none">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="coming_soon" {{ request('status') == 'coming_soon' ? 'selected' : '' }}>Coming Soon</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </form>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <a href="{{ route('admin.rewards.transactions') }}" class="px-4 py-2 text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl text-sm font-semibold transition-200">
                Transactions
            </a>
            <a href="{{ route('admin.rewards.create') }}" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold flex items-center gap-2 shadow-sm shadow-emerald-200 transition-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add New Reward
            </a>
        </div>
    </div>

    <!-- Table Content -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Reward</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Points</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Stock</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($rewards as $reward)
                <tr class="hover:bg-slate-50/50 transition-200 group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden flex-shrink-0">
                                @if($reward->image)
                                    <img src="{{ Storage::url($reward->image) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-900">{{ $reward->name }}</p>
                                <p class="text-xs text-slate-500 line-clamp-1 max-w-[200px]">{{ $reward->description }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider
                            @if($reward->category == 'physical') bg-blue-50 text-blue-600 @elseif($reward->category == 'digital') bg-purple-50 text-purple-600 @else bg-orange-50 text-orange-600 @endif">
                            {{ $reward->category }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-semibold text-sm text-slate-700">
                        {{ number_format($reward->points_required) }} pts
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        @if($reward->stock === null)
                            <span class="text-slate-400">Unlimited</span>
                        @else
                            {{ $reward->stock }} units
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <form action="{{ route('admin.rewards.toggle-status', $reward) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider transition-200
                                @if($reward->status == 'active') bg-emerald-50 text-emerald-600 hover:bg-emerald-100 
                                @elseif($reward->status == 'coming_soon') bg-amber-50 text-amber-600 hover:bg-amber-100
                                @else bg-slate-100 text-slate-500 hover:bg-slate-200 @endif">
                                {{ str_replace('_', ' ', $reward->status) }}
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.rewards.edit', $reward) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </a>
                            <form action="{{ route('admin.rewards.destroy', $reward) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this reward?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                            </div>
                            <h3 class="text-slate-900 font-bold">No rewards found</h3>
                            <p class="text-slate-500 text-sm">Try adjusting your search or filters.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100">
        {{ $rewards->links() }}
    </div>
</div>
@endsection
