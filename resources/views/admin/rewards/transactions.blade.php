@extends('admin.layouts.app')

@section('title', 'Transaction History')
@section('page_title', 'Reward Transactions')
@section('page_subtitle', 'Monitor and manage all reward redemption requests.')

@section('content')
<div class="bg-white rounded-2xl border border-slate-200 card-shadow overflow-hidden">
    <!-- Table Header/Filters -->
    <div class="p-6 border-b border-slate-100">
        <form action="{{ route('admin.rewards.transactions') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search user or reward..." class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-200">
            </div>

            <select name="reward_id" onchange="this.form.submit()" class="pl-4 pr-10 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-200 appearance-none">
                <option value="">All Reward Items</option>
                @foreach($rewards as $reward)
                    <option value="{{ $reward->id }}" {{ request('reward_id') == $reward->id ? 'selected' : '' }}>{{ $reward->name }}</option>
                @endforeach
            </select>

            <select name="status" onchange="this.form.submit()" class="pl-4 pr-10 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-200 appearance-none">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>

            <div class="relative">
                <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-200">
            </div>
        </form>
    </div>

    <!-- Table Content -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Reward Item</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Points Used</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($transactions as $transaction)
                <tr class="hover:bg-slate-50/50 transition-200 group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-[10px]">
                                {{ substr($transaction->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-900">{{ $transaction->user->name }}</p>
                                <p class="text-[10px] text-slate-500">{{ $transaction->user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-semibold text-slate-700">{{ $transaction->reward->name }}</p>
                        <p class="text-[10px] text-slate-400 capitalize">{{ $transaction->reward->category }}</p>
                    </td>
                    <td class="px-6 py-4 font-bold text-sm text-emerald-600">
                        {{ number_format($transaction->points_used) }} pts
                    </td>
                    <td class="px-6 py-4 text-xs text-slate-600">
                        {{ $transaction->created_at->format('M d, Y') }}<br>
                        <span class="text-slate-400">{{ $transaction->created_at->format('H:i') }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                            @if($transaction->status == 'pending') bg-amber-50 text-amber-600 
                            @elseif($transaction->status == 'completed') bg-emerald-50 text-emerald-600
                            @else bg-rose-50 text-rose-600 @endif">
                            {{ $transaction->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($transaction->status == 'pending')
                        <div class="flex items-center justify-end gap-2">
                            <form action="{{ route('admin.rewards.transactions.update-status', $transaction) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-200" title="Approve">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </button>
                            </form>
                            <form action="{{ route('admin.rewards.transactions.update-status', $transaction) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition-200" title="Reject">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </form>
                        </div>
                        @else
                            <span class="text-xs text-slate-400 italic">Processed</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                            </div>
                            <h3 class="text-slate-900 font-bold">No transactions found</h3>
                            <p class="text-slate-500 text-sm">Transactions will appear here when users redeem rewards.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100">
        {{ $transactions->links() }}
    </div>
</div>
@endsection
