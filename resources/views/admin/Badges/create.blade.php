@extends('admin.layouts.app')

@section('content')
<div class="p-6 flex justify-center bg-gradient-to-br from-emerald-50 via-white to-green-50 min-h-screen">

    <!-- CARD -->
    <div class="w-full max-w-3xl bg-white/90 backdrop-blur rounded-3xl shadow-xl border border-emerald-100 p-8">

        <!-- HEADER -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-emerald-700">
                ✨ Tambah Badge
            </h2>
            <p class="text-sm text-slate-500 mt-1">
                Buat badge baru untuk sistem gamifikasi TerraVerde
            </p>
        </div>

        <form action="{{ route('admin.badges.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- NAMA -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Badge</label>
                <input type="text" name="name"
                    class="w-full border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl p-3 transition"
                    placeholder="Eco Starter 🌱">
            </div>

            <!-- GRID -->
            <div class="grid grid-cols-2 gap-4">

                <!-- KATEGORI -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Kategori</label>
                    <input type="text" name="category"
                        class="w-full border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl p-3"
                        placeholder="Challenge">
                </div>

                <!-- LEVEL -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Level</label>
                    <select name="level"
                        class="w-full border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl p-3">
                        <option>Common</option>
                        <option>Rare</option>
                        <option>Epic</option>
                        <option>Legendary</option>
                    </select>
                </div>

            </div>

            <!-- DESKRIPSI -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="3"
                    class="w-full border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl p-3"
                    placeholder="Badge ini diberikan untuk..."></textarea>
            </div>

            <!-- ICON -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Icon Badge</label>

                <div class="border-2 border-dashed border-emerald-200 rounded-xl p-4 text-center hover:bg-emerald-50 transition cursor-pointer">
                    <input type="file" name="icon" class="hidden" onchange="previewImage(event)" id="upload">

                    <label for="upload" class="cursor-pointer text-sm text-slate-500">
                        📁 Klik untuk upload icon
                    </label>

                    <img id="preview" class="mx-auto mt-3 w-16 h-16 hidden rounded-full border border-emerald-200 shadow"/>
                </div>
            </div>

            <!-- STATUS -->
            <div class="flex items-center gap-3 bg-emerald-50 p-3 rounded-xl">
                <input type="checkbox" name="is_active" checked
                    class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                <label class="text-sm text-emerald-700 font-medium">Aktifkan badge</label>
            </div>

            <!-- BUTTON -->
            <div class="flex justify-between items-center pt-4">

                <a href="{{ route('admin.badges.index') }}"
                   class="text-sm text-slate-500 hover:text-emerald-600 transition">
                    ← Kembali
                </a>

                <button class="bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white px-6 py-2.5 rounded-xl shadow-lg transition">
                    🚀 Simpan Badge
                </button>

            </div>

        </form>

    </div>

</div>

<!-- PREVIEW SCRIPT -->
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