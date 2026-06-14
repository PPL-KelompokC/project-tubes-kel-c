@extends('admin.layouts.app')

@section('content')
<div class="p-6">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
            Manajemen Artikel Edukasi
        </h2>

        <a href="{{ route('admin.articles.create') }}"
           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow">
            + Tambah Artikel
        </a>
    </div>

    <!-- Notifikasi -->
    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- List Artikel -->
    @if($articles->count() > 0)

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">

            @foreach ($articles as $article)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-lg hover:scale-[1.01] transition-all duration-300 flex flex-col">

                <!-- Thumbnail -->
                <div class="relative group">
                    <img src="{{ $article->thumbnail ? asset('storage/'.$article->thumbnail) : 'https://via.placeholder.com/400x200' }}"
                         class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110">
                    
                    <div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>

                    <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm text-emerald-600 text-[10px] font-bold px-3 py-1 rounded-full shadow-sm">
                        {{ strtoupper($article->category ?? 'Lifestyle') }}
                    </span>
                </div>

                <!-- Content -->
                <div class="p-5 flex-1 flex flex-col">

                    <!-- Judul -->
                    <h3 class="font-bold text-slate-800 mb-2 line-clamp-2 leading-snug">
                        {{ $article->title }}
                    </h3>

                    <!-- Excerpt -->
                    <p class="text-xs text-slate-500 mb-4 line-clamp-3 leading-relaxed flex-1">
                        {{ $article->excerpt }}
                    </p>

                    <!-- Info -->
                    <div class="flex justify-between items-center text-[10px] font-medium text-slate-400 mb-4 pt-4 border-t border-slate-50">

                        <!-- Read Time -->
                        <div class="flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            {{ $article->created_at->diffForHumans() }}
                        </div>

                        <!-- Status -->
                        <span class="px-2.5 py-1 rounded-full border 
                            {{ $article->is_published ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-slate-50 text-slate-500 border-slate-200' }}">
                            {{ $article->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </div>


                    <!-- Footer -->
                    <div class="flex gap-2">
                        <a href="{{ route('admin.articles.edit', $article->id) }}"
                           class="flex-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 text-xs font-bold py-2.5 rounded-xl transition-colors text-center flex items-center justify-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            Edit
                        </a>

                        <form action="{{ route('admin.articles.destroy', $article->id) }}"
                              method="POST"
                              class="flex-1"
                              onsubmit="return confirm('Yakin ingin menghapus artikel ini?');">
                            @csrf
                            @method('DELETE')
                            <button class="w-full bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-bold py-2.5 rounded-xl transition-colors flex items-center justify-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></button>
                        </form>
                    </div>

                </div>
            </div>
            @endforeach

        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $articles->links() }}
        </div>

    @else

        <!-- Empty State -->
        <div class="text-center py-10">
            <p class="text-gray-500 mb-4">Belum ada artikel 😢</p>

            <a href="{{ route('admin.articles.create') }}"
               class="bg-green-600 text-white px-4 py-2 rounded-lg">
                Tambah Artikel Pertama
            </a>
        </div>

    @endif

</div>
@endsection
