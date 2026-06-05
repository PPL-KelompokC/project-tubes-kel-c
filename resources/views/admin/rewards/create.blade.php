@extends('admin.layouts.app')

@section('title', 'Create Reward')
@section('page_title', 'Create New Reward')
@section('page_subtitle', 'Add a new item to the eco-rewards catalog.')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-data="rewardForm()">
    <!-- Form Section -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-slate-200 card-shadow overflow-hidden">
            <form action="{{ route('admin.rewards.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf
                <div class="space-y-6">
                    <!-- Basic Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700">Reward Name</label>
                            <input type="text" name="name" x-model="name" required placeholder="e.g. Plant a Real Tree" 
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-200">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700">Category</label>
                            <select name="category" x-model="category" required 
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-200">
                                <option value="physical">Physical Product</option>
                                <option value="digital">Digital Reward</option>
                                <option value="donation">Donation</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Description</label>
                        <textarea name="description" x-model="description" rows="4" required placeholder="Explain what the user gets when they redeem this reward..."
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-200"></textarea>
                    </div>

                    <!-- Points and Stock -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700">Points Required</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-bold">pts</span>
                                <input type="number" name="points_required" x-model="points" required min="0" 
                                    class="w-full pl-12 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-200">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700">Stock (Optional)</label>
                            <input type="number" name="stock" x-model="stock" min="0" placeholder="Unlimited"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-200">
                            <p class="text-[10px] text-slate-400">Leave empty for unlimited stock.</p>
                        </div>
                    </div>

                    <!-- Status and Image -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700">Initial Status</label>
                            <select name="status" x-model="status" required 
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-200">
                                <option value="active">Active</option>
                                <option value="coming_soon">Coming Soon</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700">Reward Image</label>
                            <input type="file" name="image" @change="handleImage" accept="image/*"
                                class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.rewards.index') }}" class="px-6 py-2.5 text-slate-600 hover:text-slate-800 font-bold text-sm transition-200">
                            Cancel
                        </a>
                        <button type="submit" class="px-8 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-emerald-200 transition-200">
                            Create Reward
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Preview Section -->
    <div class="lg:col-span-1">
        <div class="sticky top-24">
            <h3 class="text-sm font-bold text-slate-900 mb-4 uppercase tracking-wider">Live Preview</h3>
            
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden group transition-all duration-300 hover:shadow-2xl">
                <!-- Image Container -->
                <div class="relative h-48 bg-slate-50 overflow-hidden">
                    <template x-if="imagePreview">
                        <img :src="imagePreview" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    </template>
                    <template x-if="!imagePreview">
                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    </template>
                    
                    <!-- Category Badge -->
                    <div class="absolute top-4 left-4">
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest shadow-sm backdrop-blur-md"
                            :class="{
                                'bg-blue-500/90 text-white': category === 'physical',
                                'bg-purple-500/90 text-white': category === 'digital',
                                'bg-orange-500/90 text-white': category === 'donation'
                            }" x-text="category">
                        </span>
                    </div>

                    <!-- Status Overlay -->
                    <template x-if="status === 'coming_soon'">
                        <div class="absolute inset-0 bg-slate-900/60 flex items-center justify-center backdrop-blur-[2px]">
                            <span class="px-4 py-2 bg-white/20 border border-white/30 rounded-full text-white text-xs font-bold uppercase tracking-widest">Coming Soon</span>
                        </div>
                    </template>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h4 class="text-lg font-bold text-slate-900 line-clamp-1" x-text="name || 'Reward Name'"></h4>
                        <div class="flex items-center gap-1 text-emerald-600 font-bold">
                            <span class="text-lg" x-text="points || 0"></span>
                            <span class="text-[10px] uppercase tracking-tighter">pts</span>
                        </div>
                    </div>
                    
                    <p class="text-slate-500 text-sm line-clamp-2 mb-6 h-10" x-text="description || 'No description provided yet.'"></p>

                    <button disabled class="w-full py-3 rounded-2xl font-bold text-sm transition-all duration-300 flex items-center justify-center gap-2"
                        :class="status === 'coming_soon' ? 'bg-slate-100 text-slate-400 cursor-not-allowed' : 'bg-emerald-500 text-white shadow-lg shadow-emerald-200'">
                        <span x-text="status === 'coming_soon' ? 'Notify Me' : 'Redeem Now'"></span>
                    </button>
                    
                    <div class="mt-4 flex items-center justify-between">
                        <div class="flex items-center gap-1.5 text-[11px] font-medium text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            <span x-text="stock ? stock + ' units left' : 'Unlimited stock'"></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 p-4 bg-amber-50 rounded-xl border border-amber-100 flex gap-3">
                <svg class="w-5 h-5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-xs text-amber-700 leading-relaxed">
                    This preview shows how the reward will look to users. Make sure the description is clear and the points required are fair.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function rewardForm() {
        return {
            name: '',
            category: 'physical',
            description: '',
            points: 0,
            stock: '',
            status: 'active',
            imagePreview: null,
            handleImage(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.imagePreview = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            }
        }
    }
</script>
@endsection
