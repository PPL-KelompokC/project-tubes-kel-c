@extends('admin.layouts.app')

@section('title', 'Edit User - ' . $user->name)
@section('page_title', 'Edit User')
@section('page_subtitle', 'Update user account information.')

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="bg-white rounded-2xl border border-slate-200 card-shadow overflow-hidden">
        @csrf
        @method('PUT')

        <!-- Form Content -->
        <div class="p-8 space-y-6">
            
            <!-- Name Field -->
            <div>
                <label for="name" class="block text-sm font-bold text-slate-900 mb-2">Full Name</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name', $user->name) }}"
                    placeholder="Enter user's full name..."
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-200 {{ $errors->has('name') ? 'border-red-500' : '' }}"
                />
                @if($errors->has('name'))
                    <p class="text-red-600 text-xs mt-1.5">{{ $errors->first('name') }}</p>
                @endif
            </div>

            <!-- Email Field -->
            <div>
                <label for="email" class="block text-sm font-bold text-slate-900 mb-2">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email', $user->email) }}"
                    placeholder="Enter email address..."
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-200 {{ $errors->has('email') ? 'border-red-500' : '' }}"
                />
                @if($errors->has('email'))
                    <p class="text-red-600 text-xs mt-1.5">{{ $errors->first('email') }}</p>
                @endif
            </div>

            <!-- Location Field -->
            <div>
                <label for="location" class="block text-sm font-bold text-slate-900 mb-2">Location</label>
                <input 
                    type="text" 
                    id="location" 
                    name="location" 
                    value="{{ old('location', $user->location) }}"
                    placeholder="Enter user's location..."
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-200"
                />
            </div>

            <!-- Password Section -->
            <div class="pt-4 border-t border-slate-200">
                <h3 class="text-sm font-bold text-slate-900 mb-4">Ubah Password (Opsional)</h3>
                
                <div class="space-y-4">
                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-bold text-slate-900 mb-2">Password Baru</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="Kosongkan jika tidak ingin mengubah password..."
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-200 {{ $errors->has('password') ? 'border-red-500' : '' }}"
                        />
                        @if($errors->has('password'))
                            <p class="text-red-600 text-xs mt-1.5">{{ $errors->first('password') }}</p>
                        @endif
                    </div>

                    <!-- Confirm Password Field -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-bold text-slate-900 mb-2">Konfirmasi Password</label>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            placeholder="Konfirmasi password baru..."
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-200"
                        />
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="p-4 bg-blue-50 border border-blue-100 rounded-xl">
                <p class="text-xs text-blue-700">
                    <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                    Joined: {{ $user->created_at->format('d M Y H:i') }}
                </p>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="px-8 py-4 bg-slate-50 border-t border-slate-200 flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white rounded-xl font-semibold hover:bg-emerald-700 transition-200">
                Save Changes
            </button>
            <a href="{{ route('admin.users.show', $user) }}" class="px-6 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl font-semibold hover:bg-slate-50 transition-200">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
