@extends('admin.layouts.app')

@section('content')
<div class="p-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Badge Management</h2>
            <p class="text-sm text-slate-500">Kelola badge dan sistem gamifikasi user</p>
        </div>

        <a href="{{ route('admin.badges.create') }}"
           class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-xl shadow transition">
            + Tambah Badge
        </a>
    </div>

    <!-- NOTIF -->
    @if(session('success'))
        <div class="bg-emerald-100 text-emerald-700 px-4 py-2 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    @php
        $levels = [
            'Common' => ['color' => 'gray', 'icon' => '🟤'],
            'Rare' => ['color' => 'blue', 'icon' => '🔵'],
            'Epic' => ['color' => 'purple', 'icon' => '🟣'],
            'Legendary' => ['color' => 'yellow', 'icon' => '🟡'],
        ];
    @endphp

    <!-- LOOP PER LEVEL -->
    @foreach($levels as $level => $meta)

        @if(isset($badges[$level]) && $badges[$level]->count())

        <!-- SECTION -->
        <div class="mb-10 border-l-4 pl-5 py-5 rounded-xl
            {{ $meta['color'] == 'gray' ? 'border-gray-300 bg-gray-50/40' :
               ($meta['color'] == 'blue' ? 'border-blue-300 bg-blue-50/40' :
               ($meta['color'] == 'purple' ? 'border-purple-300 bg-purple-50/40' :
               'border-yellow-300 bg-yellow-50/40')) }}">

            <!-- HEADER LEVEL -->
            <div class="flex items-center justify-between mb-6">

                <div class="flex items-center gap-3">

                    <!-- ICON BULAT -->
                    <div class="w-9 h-9 rounded-full flex items-center justify-center
                        {{ $meta['color'] == 'gray' ? 'bg-gray-200' :
                           ($meta['color'] == 'blue' ? 'bg-blue-200' :
                           ($meta['color'] == 'purple' ? 'bg-purple-200' :
                           'bg-yellow-200')) }}">
                        {{ $meta['icon'] }}
                    </div>

                    <!-- TITLE -->
                    <h3 class="text-lg font-bold text-{{ $meta['color'] }}-700">
                        {{ $level }} Badges
                    </h3>

                </div>

                <!-- COUNT -->
                <span class="text-sm bg-white px-3 py-1 rounded-full shadow text-gray-500">
                    {{ $badges[$level]->count() }} badge
                </span>

            </div>

            <!-- GRID -->
            <div class="grid md:grid-cols-3 lg:grid-cols-4 gap-6">

                @foreach($badges[$level] as $badge)

                <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg hover:scale-105 transition p-5 relative group">

                    <!-- LEVEL LABEL -->
                    <span class="absolute top-3 right-3 text-xs px-3 py-1 rounded-full
                        {{ $level == 'Common' ? 'bg-gray-100 text-gray-600' :
                           ($level == 'Rare' ? 'bg-blue-100 text-blue-600' :
                           ($level == 'Epic' ? 'bg-purple-100 text-purple-600' :
                           'bg-yellow-100 text-yellow-700')) }}">
                        {{ $level }}
                    </span>

                    <!-- ICON -->
                    <div class="flex justify-center mb-4">
                        <div class="w-16 h-16 rounded-full bg-emerald-50 flex items-center justify-center">
                            <img src="{{ $badge->icon ? asset('storage/'.$badge->icon) : 'https://via.placeholder.com/80' }}"
                                 class="w-10 h-10 object-contain">
                        </div>
                    </div>

                    <!-- NAME -->
                    <h3 class="text-center font-semibold text-slate-800">
                        {{ $badge->name }}
                    </h3>

                    <!-- CATEGORY -->
                    <p class="text-center text-xs text-slate-400 mb-2">
                        {{ $badge->category ?? 'General' }}
                    </p>

                    <!-- DESCRIPTION -->
                    <p class="text-xs text-slate-500 text-center mb-4 line-clamp-2">
                        {{ $badge->description ?? 'Tidak ada deskripsi' }}
                    </p>

                    <!-- STATUS -->
                    <div class="flex justify-center">
                        <span class="text-xs px-3 py-1 rounded-full
                            {{ $badge->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }}">
                            {{ $badge->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>

                    <!-- ACTION -->
                    <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition rounded-2xl flex items-center justify-center gap-3">

                        <a href="{{ route('admin.badges.edit', $badge->id) }}"
                           class="bg-white px-3 py-1 text-sm rounded shadow hover:bg-slate-100">
                            Edit
                        </a>

                        <form action="{{ route('admin.badges.destroy', $badge->id) }}"
                              method="POST"
                              onsubmit="return confirm('Hapus badge ini?')">
                            @csrf
                            @method('DELETE')

                            <button class="bg-red-500 text-white px-3 py-1 text-sm rounded hover:bg-red-600">
                                Hapus
                            </button>
                        </form>

                    </div>

                </div>

                @endforeach

            </div>

        </div>

        @endif

    @endforeach

    <!-- EMPTY -->
    @if($badges->count() == 0)
        <div class="text-center py-10 text-slate-500">
            Belum ada badge 😢
        </div>
    @endif

</div>
@endsection