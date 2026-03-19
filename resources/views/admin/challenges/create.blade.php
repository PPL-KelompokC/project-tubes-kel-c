@extends('admin.layouts.app')

@section('title', 'New Challenge')
@section('page_title', 'Create New Challenge')
@section('page_subtitle', 'Add a new daily challenge to the platform.')

@section('content')
<div class="max-w-3xl">
    <form action="{{ route('admin.challenges.store') }}" method="POST">
        @csrf
        <div class="bg-white rounded-3xl border border-slate-200 card-shadow overflow-hidden">
            <div class="p-8 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-900">General Information</h3>
                <p class="text-xs text-slate-500 mt-1">Define the basic details and requirements of the challenge.</p>
            </div>
            
            <div class="p-8 space-y-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Challenge Title</label>
                    <input type="text" name="title" placeholder="e.g., Bike to Work" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-200" required>
                </div>
                
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Description</label>
                    <textarea name="description" rows="4" placeholder="Describe what the user needs to do..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-200" required></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Category</label>
                        <select name="category" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-200">
                            <option value="transport">Transport</option>
                            <option value="food">Food</option>
                            <option value="waste">Waste</option>
                            <option value="energy">Energy</option>
                            <option value="water">Water</option>
                            <option value="nature">Nature</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Difficulty Level</label>
                        <select name="difficulty" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-200">
                            <option value="easy">Easy</option>
                            <option value="medium">Medium</option>
                            <option value="hard">Hard</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Points Reward</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">⭐</span>
                            <input type="number" name="points" placeholder="50" class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-200" required min="0">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">CO₂ Saved (kg)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">🌱</span>
                            <input type="number" step="0.01" name="co2_saved" placeholder="2.3" class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-200" required min="0">
                        </div>
                    </div>
                </div>

                <div class="space-y-2 pt-4 border-t border-slate-100">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Image URL</label>
                    <input type="url" name="image_url" placeholder="https://images.unsplash.com/..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-200">
                </div>
            </div>

            <div class="p-8 bg-slate-50/50 border-t border-slate-100 flex items-center justify-end gap-4">
                <a href="{{ route('admin.challenges.index') }}" class="px-6 py-2.5 text-sm font-bold text-slate-500 hover:text-slate-800 transition-200">Discard</a>
                <button type="submit" class="bg-emerald-600 text-white px-8 py-2.5 rounded-xl text-sm font-bold hover:bg-emerald-700 transition-200 shadow-lg shadow-emerald-100">
                    Create Challenge
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
