@extends('admin.layouts.app')

@section('title', 'User Detail - ' . $user->name)
@section('page_title', $user->name)
@section('page_subtitle', 'View and manage user account information.')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- User Info Card -->
        <div class="bg-white rounded-2xl border border-slate-200 card-shadow p-8">
            <div class="flex items-start gap-6 mb-8">
                <div class="w-20 h-20 rounded-full bg-emerald-100 border-2 border-emerald-200 flex items-center justify-center text-emerald-700 font-bold text-3xl">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div class="flex-1">
                    <h2 class="text-2xl font-bold text-slate-900">{{ $user->name }}</h2>
                    <p class="text-slate-600 text-sm mt-1">{{ $user->email }}</p>
                    <div class="flex items-center gap-3 mt-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold">
                            <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                            Active User
                        </span>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="border-t border-slate-200 my-6"></div>

            <!-- User Details -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-600">Email</span>
                    <span class="text-sm font-medium text-slate-900">{{ $user->email }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-600">Location</span>
                    <span class="text-sm font-medium text-slate-900">{{ $user->location ?? '-' }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-600">Role</span>
                    <span class="text-sm font-medium text-slate-900 capitalize">{{ $user->role }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-600">Member Since</span>
                    <span class="text-sm font-medium text-slate-900">{{ $user->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-white rounded-2xl border border-slate-200 card-shadow p-6 text-center">
                <p class="text-xs font-bold text-slate-400 uppercase mb-2">Points</p>
                <p class="text-3xl font-black text-sky-600">{{ number_format($user->points) }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 card-shadow p-6 text-center">
                <p class="text-xs font-bold text-slate-400 uppercase mb-2">CO₂ Saved</p>
                <p class="text-3xl font-black text-emerald-600">{{ number_format($user->carbon_saved, 1) }}<span class="text-sm">kg</span></p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 card-shadow p-6 text-center">
                <p class="text-xs font-bold text-slate-400 uppercase mb-2">Current Streak</p>
                <p class="text-3xl font-black text-orange-600">{{ $user->streak }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 card-shadow p-6 text-center">
                <p class="text-xs font-bold text-slate-400 uppercase mb-2">Longest Streak</p>
                <p class="text-3xl font-black text-amber-600">{{ $user->longest_streak }}</p>
            </div>
        </div>

        <!-- Challenges Completed -->
        <div class="bg-white rounded-2xl border border-slate-200 card-shadow p-6">
            <h3 class="text-lg font-bold text-slate-900 mb-4">Challenges Completed</h3>
            <p class="text-3xl font-black text-indigo-600">{{ $user->challenges_completed }}</p>
        </div>

    </div>

    <!-- Sidebar Actions -->
    <div class="space-y-4">
        
        <!-- Edit Button -->
        <a href="{{ route('admin.users.edit', $user) }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-emerald-600 text-white rounded-xl font-semibold hover:bg-emerald-700 transition-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit User
        </a>

        <!-- Delete Button (with custom modal) -->
        <button 
            @click="openDeleteModal = true"
            class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-red-50 text-red-600 rounded-xl font-semibold hover:bg-red-100 transition-200 border border-red-200"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Delete User
        </button>

        <!-- Back Button -->
        <a href="{{ route('admin.users.index') }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-xl font-semibold hover:bg-slate-50 transition-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Users
        </a>

    </div>

</div>

<!-- Delete Confirmation Modal -->
<div x-show="openDeleteModal" @click.away="openDeleteModal = false" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" style="display: none;">
    <div @click.stop class="bg-white rounded-2xl shadow-xl max-w-md w-full p-8 animate-in fade-in zoom-in-95 duration-200">
        
        <!-- Header -->
        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100 mx-auto mb-4">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2M6.228 6.228a9 9 0 1112.544 12.544M6.228 6.228l5.656 5.656m0 0l5.656 5.656M6.228 17.884l5.656-5.656m0 0l5.656 5.656"/></svg>
        </div>

        <!-- Content -->
        <h3 class="text-lg font-bold text-slate-900 text-center mb-2">Delete User?</h3>
        <p class="text-slate-600 text-sm text-center mb-6">
            Apakah Anda yakin ingin menghapus user <strong>{{ $user->name }}</strong>? Tindakan ini tidak dapat dibatalkan.
        </p>

        <!-- Actions -->
        <div class="flex items-center gap-3">
            <button 
                @click="openDeleteModal = false"
                class="flex-1 px-4 py-2.5 bg-slate-100 text-slate-700 rounded-lg font-semibold hover:bg-slate-200 transition-200"
            >
                Cancel
            </button>
            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button 
                    type="submit"
                    class="w-full px-4 py-2.5 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition-200"
                >
                    Delete
                </button>
            </form>
        </div>

    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('userDetail', () => ({
            openDeleteModal: false,
        }))
    })
</script>
@endsection
