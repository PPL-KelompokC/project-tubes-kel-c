@extends('admin.layouts.app')

@section('content')
<div class="p-6 max-w-4xl mx-auto">

    <!-- HEADER -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-emerald-700">Tambah Artikel Edukasi</h2>
        <p class="text-sm text-slate-500 mt-1">Buat dan bagikan artikel baru untuk komunitas TerraVerde</p>
    </div>

    <!-- FORM CARD -->
    <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data"
          class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 space-y-6">
        @csrf

        <!-- Judul -->
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Judul Artikel</label>
            <input type="text" name="title"
                   class="w-full border border-slate-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 rounded-xl p-3.5 transition-all outline-none"
                   placeholder="Contoh: 10 Cara Mengurangi Limbah Plastik"
                   required>
        </div>

        <!-- Kategori -->
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Kategori</label>
                <input type="text" name="category"
                       class="w-full border border-slate-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 rounded-xl p-3.5 transition-all outline-none"
                       placeholder="Lifestyle, Health, Energy, dll">
            </div>
        </div>

        <!-- Excerpt / sinopsis -->
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Sinopsis Singkat</label>
            <textarea name="excerpt"
                      rows="2"
                      class="w-full border border-slate-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 rounded-xl p-3.5 transition-all outline-none"
                      placeholder="Ringkasan singkat artikel untuk ditampilkan di kartu..."></textarea>
        </div>

        <!-- Konten -->
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Isi Konten</label>
            <textarea name="content"
                      class="w-full border border-slate-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 rounded-xl p-3.5 transition-all outline-none"
                      rows="8"
                      placeholder="Tulis isi lengkap artikel edukasi di sini..."
                      required></textarea>
        </div>

        <!-- Thumbnail -->
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Gambar Thumbnail</label>

            <div class="relative group">
                <div class="border-2 border-dashed border-slate-200 group-hover:border-emerald-400 rounded-2xl p-8 text-center transition-all bg-slate-50/50 group-hover:bg-emerald-50/30">
                    <input type="file" name="thumbnail"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                           id="thumbnailInput"
                           onchange="previewImage(event)">

                    <div class="flex flex-col items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm font-bold text-slate-700">Klik untuk upload gambar</p>
                            <p class="text-xs text-slate-500 text-center">Format: JPG, PNG, WEBP (Maks. 2MB)</p>
                        </div>
                    </div>

                    <div id="previewContainer" class="mt-6 hidden">
                        <img id="preview" class="mx-auto max-h-48 rounded-xl shadow-md border-4 border-white"/>
                    </div>
                </div>
            </div>
        </div>

        <!-- Publish -->
        <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl border border-slate-100">
            <input type="checkbox" name="is_published" checked id="is_published"
                   class="w-5 h-5 accent-emerald-600 rounded cursor-pointer">
            <label for="is_published" class="text-sm font-semibold text-slate-700 cursor-pointer">Publikasikan artikel ini sekarang</label>
        </div>

        <!-- BUTTON -->
        <div class="flex justify-between items-center pt-6 border-t border-slate-100">

            <a href="{{ route('admin.articles.index') }}"
               class="text-sm font-bold text-slate-400 hover:text-emerald-600 transition-colors flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Kembali
            </a>

            <button class="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white px-8 py-3 rounded-xl shadow-lg shadow-emerald-200 transition-all active:scale-95 font-bold flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Simpan Artikel
            </button>

        </div>

    </form>
</div>

<!-- Preview Script -->
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
