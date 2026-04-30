@extends('admin.layouts.app')

@section('title', 'User Management')
@section('page_title', 'Users')
@section('page_subtitle', 'Monitor and manage user accounts and their performance.')

@section('content')
<div class="space-y-6" x-data="userManagement()">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <form action="{{ route('admin.users.index') }}" method="GET" class="relative max-w-md w-full">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-200 shadow-sm" onchange="this.form.submit()">
        </form>
        
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-emerald-700 transition-200 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Create User
            </a>
            <a href="{{ route('admin.users.export') }}" class="inline-flex items-center gap-2 bg-white border border-slate-200 text-slate-600 px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-slate-50 transition-200 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export CSV
            </a>
        </div>
    </div>

    <!-- User Table -->
    <div class="bg-white rounded-[2rem] border border-slate-200 card-shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">User</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Statistics</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Impact</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/50 transition-200 group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-emerald-100 border border-emerald-200 flex items-center justify-center text-emerald-700 font-bold text-sm">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-slate-900 truncate">{{ $user->name }}</p>
                                        <p class="text-xs text-slate-500 mt-0.5 truncate">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-6">
                                    <div class="text-center">
                                        <p class="text-xs font-black text-slate-900">{{ number_format($user->points) }}</p>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase">Points</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs font-black text-orange-500">{{ $user->streak }}</p>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase">Streak</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-100">
                                    -{{ number_format($user->carbon_saved, 1) }}kg CO₂
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Active
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- View Detail -->
                                    <a href="{{ route('admin.users.show', $user) }}" class="p-2 text-slate-400 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-200" title="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <!-- Edit -->
                                    <a href="{{ route('admin.users.edit', $user) }}" class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-200" title="Edit User">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <!-- Delete -->
                                    <button 
                                        @click="openDeleteModal({{ $user->id }}, '{{ $user->name }}')"
                                        class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-200" 
                                        title="Delete User"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center text-slate-400">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
                {{ $users->withQueryString()->links() }}
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    <template x-if="showDeleteModal">
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div @click.stop class="bg-white rounded-2xl shadow-xl max-w-md w-full p-8 animate-in fade-in zoom-in-95 duration-200">
                
                <!-- Header -->
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100 mx-auto mb-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2M6.228 6.228a9 9 0 1112.544 12.544M6.228 6.228l5.656 5.656m0 0l5.656 5.656M6.228 17.884l5.656-5.656m0 0l5.656 5.656"/></svg>
                </div>

                <!-- Content -->
                <h3 class="text-lg font-bold text-slate-900 text-center mb-2">Delete User?</h3>
                <p class="text-slate-600 text-sm text-center mb-6">
                    Apakah Anda yakin ingin menghapus user <strong x-text="deleteUserName"></strong>? Tindakan ini tidak dapat dibatalkan.
                </p>

                <!-- Actions -->
                <div class="flex items-center gap-3">
                    <button 
                        @click="showDeleteModal = false"
                        class="flex-1 px-4 py-2.5 bg-slate-100 text-slate-700 rounded-lg font-semibold hover:bg-slate-200 transition-200"
                    >
                        Cancel
                    </button>
                    <form :action="`{{ route('admin.users.destroy', '__USER_ID__') }}`.replace('__USER_ID__', deleteUserId)" method="POST" class="flex-1">
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
    </template>

</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('userManagement', () => ({
            showDeleteModal: false,
            deleteUserId: null,
            deleteUserName: '',
            
            openDeleteModal(userId, userName) {
                this.deleteUserId = userId;
                this.deleteUserName = userName;
                this.showDeleteModal = true;
            }
        }))
    })
</script>
@endsection
