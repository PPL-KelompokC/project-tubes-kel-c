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
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-amber-400">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </span>
                            <input type="number" name="points" value="{{ old('points', $challenge->points) }}" class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-200" required min="0">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">CO₂ Saved (kg)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-emerald-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </span>
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
