@extends('admin.layouts.app')

@section('title', 'Challenges')
@section('page_title', 'Daily Challenges')
@section('page_subtitle', 'Create, update, and manage daily eco-challenges for your users.')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-3">
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input type="text" placeholder="Filter challenges..." class="pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-200 w-64 shadow-sm">
            </div>
            <select class="text-sm bg-white border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 shadow-sm font-medium text-slate-600">
                <option>All Categories</option>
                <option>Transport</option>
                <option>Energy</option>
                <option>Nature</option>
            </select>
        </div>
        
        <a href="{{ route('admin.challenges.create') }}" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-emerald-700 transition-200 shadow-lg shadow-emerald-100 group">
            <svg class="w-4 h-4 group-hover:rotate-90 transition-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Create Challenge
        </a>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-[2rem] border border-slate-200 card-shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Challenge Details</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Category</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Rewards</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Difficulty</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($challenges as $challenge)
                        <tr class="hover:bg-slate-50/50 transition-200 group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl overflow-hidden bg-slate-100 flex-shrink-0 border border-slate-100">
                                        @if($challenge->image_url)
                                            <img src="{{ $challenge->image_url }}" class="w-full h-full object-cover" alt="">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-slate-900 truncate">{{ $challenge->title }}</p>
                                        <p class="text-xs text-slate-500 mt-1 line-clamp-1 max-w-[200px]">{{ $challenge->description }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                @php
                                    $catColors = [
                                        'transport' => 'bg-blue-50 text-blue-600 border-blue-100',
                                        'food' => 'bg-green-50 text-green-600 border-green-100',
                                        'waste' => 'bg-orange-50 text-orange-600 border-orange-100',
                                        'energy' => 'bg-yellow-50 text-yellow-600 border-yellow-100',
                                        'nature' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                    ];
                                    $color = $catColors[$challenge->category] ?? 'bg-slate-50 text-slate-600 border-slate-100';
                                @endphp
                                <span class="px-3 py-1 rounded-lg border {{ $color }} text-[10px] font-black uppercase tracking-wider">
                                    {{ $challenge->category }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="space-y-1">
                                    <p class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                                        <span class="text-emerald-500">⭐</span> {{ $challenge->points }} pts
                                    </p>
                                    <p class="text-[10px] font-medium text-emerald-600 flex items-center gap-1.5">
                                        <span class="text-emerald-400">🌱</span> -{{ $challenge->co2_saved }}kg CO₂
                                    </p>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                @php
                                    $diffColors = [
                                        'easy' => 'text-emerald-600 bg-emerald-50',
                                        'medium' => 'text-amber-600 bg-amber-50',
                                        'hard' => 'text-rose-600 bg-rose-50',
                                    ];
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full {{ $diffColors[$challenge->difficulty] ?? 'bg-slate-50 text-slate-600' }} text-[10px] font-bold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    {{ ucfirst($challenge->difficulty) }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.challenges.edit', $challenge) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-200" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.challenges.destroy', $challenge) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-200" title="Delete" onclick="return confirm('Are you sure you want to delete this challenge?')">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-300">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                    </div>
                                    <p class="text-slate-400 text-sm font-medium">No challenges found. Create your first one!</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($challenges->hasPages())
            <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
                {{ $challenges->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
