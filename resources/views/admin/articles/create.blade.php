@extends('admin.layouts.app')

@section('content')
<div class="p-6 max-w-4xl mx-auto">

    <!-- HEADER -->
    <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white p-6 rounded-2xl shadow mb-6">
        <h2 class="text-2xl font-bold">Tambah Artikel Edukasi</h2>
        <p class="text-sm opacity-90">Buat dan bagikan artikel baru 🌱</p>
    </div>

    <!-- FORM CARD -->
    <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data"
          class="bg-white p-6 rounded-2xl shadow space-y-6">
        @csrf

        <!-- Judul -->
        <div>
            <label class="block mb-1 font-semibold text-gray-700">Judul</label>
            <input type="text" name="title"
                   class="w-full border p-3 rounded-xl focus:ring-2 focus:ring-green-400 focus:outline-none"
                   placeholder="Masukkan judul artikel..."
                   required>
        </div>

        <!-- Kategori -->
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Kategori</label>
                <input type="text" name="category"
                       class="w-full border p-3 rounded-xl focus:ring-2 focus:ring-green-400"
                       placeholder="Lifestyle, Health, dll">
            </div>
        </div>

        <!-- Excerpt / sinopsis -->
        <div>
            <label class="block mb-1 font-semibold text-gray-700">Sinopsis</label>
            <textarea name="excerpt"
                      class="w-full border p-3 rounded-xl focus:ring-2 focus:ring-green-400"
                      placeholder="Ringkasan singkat artikel..."></textarea>
        </div>

        <!-- Konten -->
        <div>
            <label class="block mb-1 font-semibold text-gray-700">Konten</label>
            <textarea name="content"
                      class="w-full border p-3 rounded-xl focus:ring-2 focus:ring-green-400"
                      rows="6"
                      placeholder="Tulis isi artikel di sini..."
                      required></textarea>
        </div>

        <!-- Thumbnail -->
        <div>
            <label class="block mb-1 font-semibold text-gray-700">Thumbnail</label>

            <div class="border-2 border-dashed border-green-300 rounded-xl p-4 text-center cursor-pointer hover:bg-green-50">
                <input type="file" name="thumbnail"
                       class="hidden"
                       id="thumbnailInput"
                       onchange="previewImage(event)">

                <label for="thumbnailInput" class="cursor-pointer text-green-600">
                    Klik untuk upload gambar 📷
                </label>

                <img id="preview" class="mt-4 mx-auto w-40 rounded-lg hidden"/>
            </div>
        </div>

        <!-- Publish -->
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_published" checked
                   class="accent-green-600">
            <label class="text-gray-700">Publish sekarang</label>
        </div>

        <!-- BUTTON -->
        <div class="flex justify-between items-center pt-4">

            <a href="{{ route('admin.articles.index') }}"
               class="text-gray-500 hover:underline">
                ← Kembali
            </a>

            <button class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-xl shadow">
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
        const output = document.getElementById('preview');
        output.src = reader.result;
        output.classList.remove('hidden');
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>

@endsection