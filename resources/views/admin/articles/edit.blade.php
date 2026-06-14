@extends('admin.layouts.app')

@section('content')
<div class="p-6 max-w-4xl mx-auto">

    <!-- HEADER -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-emerald-700">Edit Artikel Edukasi</h2>
        <p class="text-sm text-slate-500 mt-1">Perbarui konten artikel agar tetap relevan bagi komunitas</p>
    </div>

    <form action="{{ route('admin.articles.update', $article->id) }}"
          method="POST" enctype="multipart/form-data"
          class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 space-y-6">
        @csrf
        @method('PUT')

        <!-- Judul -->
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Judul Artikel</label>
            <input type="text" name="title"
                   value="{{ $article->title }}"
                   class="w-full border border-slate-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 rounded-xl p-3.5 transition-all outline-none"
                   required>
        </div>

        <!-- Kategori -->
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Kategori</label>
            <input type="text" name="category"
                   value="{{ $article->category }}"
                   class="w-full border border-slate-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 rounded-xl p-3.5 transition-all outline-none">
        </div>

        <!-- Sinopsis -->
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Sinopsis Singkat</label>
            <textarea name="excerpt"
                      rows="2"
                      class="w-full border border-slate-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 rounded-xl p-3.5 transition-all outline-none">{{ $article->excerpt }}</textarea>
        </div>

        <!-- Konten -->
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Isi Konten</label>
            <textarea name="content"
                      class="w-full border border-slate-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 rounded-xl p-3.5 transition-all outline-none"
                      rows="8"
                      required>{{ $article->content }}</textarea>
        </div>

        <!-- Thumbnail -->
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Gambar Thumbnail</label>
            
            <div class="grid md:grid-cols-2 gap-6 items-start">
                <!-- Current Thumbnail -->
                <div class="space-y-2">
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Thumbnail Saat Ini</p>
                    <div class="relative rounded-2xl overflow-hidden border-4 border-white shadow-md aspect-video">
                        <img src="{{ $article->thumbnail ? asset('storage/'.$article->thumbnail) : 'https://via.placeholder.com/400x200' }}"
                             class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- Upload New -->
                <div class="space-y-2">
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Ganti Thumbnail</p>
                    <div class="relative group">
                        <div class="border-2 border-dashed border-slate-200 group-hover:border-emerald-400 rounded-2xl p-6 text-center transition-all bg-slate-50/50 group-hover:bg-emerald-50/30 h-[150px] flex flex-col justify-center items-center">
                            <input type="file" name="thumbnail"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                   id="thumbnailInput"
                                   onchange="previewImage(event)">

                            <div class="flex flex-col items-center gap-2">
                                <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                </div>
                                <p class="text-xs font-bold text-slate-700">Pilih file baru</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preview New Image -->
            <div id="previewContainer" class="mt-6 hidden">
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-2">Pratinjau Baru</p>
                <img id="preview" class="max-h-48 rounded-xl shadow-md border-4 border-white"/>
            </div>
        </div>

        <!-- Publish --> 
        <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl border border-slate-100">
            <input type="checkbox" name="is_published" id="is_published"
                   {{ $article->is_published ? 'checked' : '' }}
                   class="w-5 h-5 accent-emerald-600 rounded cursor-pointer">
            <label for="is_published" class="text-sm font-semibold text-slate-700 cursor-pointer">Publikasikan artikel ini</label>
        </div>

        <!-- Button -->
        <div class="flex justify-between items-center pt-6 border-t border-slate-100">
            <a href="{{ route('admin.articles.index') }}"
               class="text-sm font-bold text-slate-400 hover:text-emerald-600 transition-colors flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Kembali
            </a>

            <button class="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white px-8 py-3 rounded-xl shadow-lg shadow-emerald-200 transition-all active:scale-95 font-bold flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Update Artikel
            </button>
        </div>

    </form>
</div>

<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const container = document.getElementById('previewContainer');
        const output = document.getElementById('preview');
        output.src = reader.result;
        container.classList.remove('hidden');
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>

@endsection
