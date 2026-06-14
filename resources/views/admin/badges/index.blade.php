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
            'Common' => ['color' => 'gray', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>'],
            'Rare' => ['color' => 'blue', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/></svg>'],
            'Epic' => ['color' => 'purple', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/><path d="M5 5l1.5 1.5"/><path d="M17.5 17.5L19 19"/><path d="M19 5l-1.5 1.5"/><path d="M6.5 17.5L5 19"/></svg>'],
            'Legendary' => ['color' => 'yellow', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>'],
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
                        {{ $meta['color'] == 'gray' ? 'bg-gray-200 text-gray-600' :
                           ($meta['color'] == 'blue' ? 'bg-blue-200 text-blue-600' :
                           ($meta['color'] == 'purple' ? 'bg-purple-200 text-purple-600' :
                           'bg-yellow-200 text-yellow-600')) }}">
                        {!! $meta['icon'] !!}
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
                        <div class="w-16 h-16 rounded-full bg-emerald-50 flex items-center justify-center border border-emerald-100 shadow-inner">
                            @if($badge->icon)
                                <img src="{{ asset('storage/'.$badge->icon) }}"
                                     class="w-12 h-12 object-contain rounded-lg">
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-300"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                            @endif
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
