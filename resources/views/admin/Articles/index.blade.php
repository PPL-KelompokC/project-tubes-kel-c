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

        <div class="grid md:grid-cols-2 gap-6">

            @foreach ($articles as $article)
            <div class="bg-white rounded-2xl shadow overflow-hidden hover:shadow-lg transition">

                <!-- Thumbnail -->
                <div class="relative">
                    <img src="{{ $article->thumbnail ? asset('storage/'.$article->thumbnail) : 'https://via.placeholder.com/400x200' }}"
                         class="w-full h-48 object-cover">

                    <span class="absolute top-3 left-3 bg-pink-100 text-pink-600 text-xs px-3 py-1 rounded-full">
                        {{ $article->category ?? 'Lifestyle' }}
                    </span>
                </div>

                <!-- Content -->
                <div class="p-4">

                    <!-- Judul -->
                    <h3 class="font-bold text-lg text-gray-800 mb-2">
                        {{ $article->title }}
                    </h3>

                    <!-- Excerpt -->
                    <p class="text-sm text-gray-500 mb-3">
                        {{ \Illuminate\Support\Str::limit($article->excerpt, 100) }}
                    </p>

                    <!-- Info -->
                    <div class="flex justify-between items-center text-sm text-gray-400 mb-3">

                        <!-- Read Time -->
                        <div>
                            {{ $article->created_at->diffForHumans() }}
                        </div>

                        <!-- Status -->
                        <span class="px-2 py-1 rounded-full text-xs
                            {{ $article->is_published ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
                            {{ $article->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </div>


                    <!-- Footer -->
                    <div class="flex justify-between items-center">

                        <div class="flex gap-3">

                            <a href="{{ route('admin.articles.edit', $article->id) }}"
                               class="text-blue-600 hover:underline">
                                Edit
                            </a>

                            <form action="{{ route('admin.articles.destroy', $article->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus artikel ini?');">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline">
                                    Hapus
                                </button>
                            </form>

                        </div>

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