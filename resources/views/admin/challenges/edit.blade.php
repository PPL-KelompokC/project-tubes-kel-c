@extends('admin.layouts.app')

@section('title', 'Edit Challenge')
@section('page_title', 'Update Challenge')
@section('page_subtitle', 'Modify existing challenge details and rewards.')

@section('content')
<div class="max-w-3xl">
    <form action="{{ route('admin.challenges.update', $challenge) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="bg-white rounded-3xl border border-slate-200 card-shadow overflow-hidden">
            <div class="p-8 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Edit Details</h3>
                    <p class="text-xs text-slate-500 mt-1">Challenge ID: #{{ $challenge->id }}</p>
                </div>
                <div class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-bold uppercase tracking-wider border border-emerald-100">
                    Active
                </div>
            </div>
            
            <div class="p-8 space-y-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Challenge Title</label>
                    <input type="text" name="title" value="{{ old('title', $challenge->title) }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-200" required>
                </div>
                
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Description</label>
                    <textarea name="description" rows="4" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-200" required>{{ old('description', $challenge->description) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Category</label>
                        <select name="category" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-200">
                            @foreach(['transport', 'food', 'waste', 'energy', 'water', 'nature'] as $cat)
                                <option value="{{ $cat }}" {{ old('category', $challenge->category) == $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Difficulty Level</label>
                        <select name="difficulty" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-200">
                            @foreach(['easy', 'medium', 'hard'] as $diff)
                                <option value="{{ $diff }}" {{ old('difficulty', $challenge->difficulty) == $diff ? 'selected' : '' }}>{{ ucfirst($diff) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Points Reward</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">⭐</span>
                            <input type="number" name="points" value="{{ old('points', $challenge->points) }}" class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-200" required min="0">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">CO₂ Saved (kg)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">🌱</span>
                            <input type="number" step="0.01" name="co2_saved" value="{{ old('co2_saved', $challenge->co2_saved) }}" class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-200" required min="0">
                        </div>
                    </div>
                </div>

                <div class="space-y-2 pt-4 border-t border-slate-100">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Image URL</label>
                    <input type="url" name="image_url" value="{{ old('image_url', $challenge->image_url) }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-200">
                </div>
            </div>

            <div class="p-8 bg-slate-50/50 border-t border-slate-100 flex items-center justify-end gap-4">
                <a href="{{ route('admin.challenges.index') }}" class="px-6 py-2.5 text-sm font-bold text-slate-500 hover:text-slate-800 transition-200">Cancel Changes</a>
                <button type="submit" class="bg-emerald-600 text-white px-8 py-2.5 rounded-xl text-sm font-bold hover:bg-emerald-700 transition-200 shadow-lg shadow-emerald-100">
                    Update Challenge
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
