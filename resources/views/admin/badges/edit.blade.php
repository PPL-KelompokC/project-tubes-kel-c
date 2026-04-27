@extends('admin.layouts.app')

@section('content')
<div class="p-6 flex justify-center bg-gradient-to-br from-emerald-50 via-white to-green-50 min-h-screen">

    <!-- CARD -->
    <div class="w-full max-w-3xl bg-white/90 backdrop-blur rounded-3xl shadow-xl border border-emerald-100 p-8">

        <!-- HEADER -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-emerald-700">
                Edit Badge
            </h2>
            <p class="text-sm text-slate-500 mt-1">
                Update informasi badge
            </p>
        </div>

        <form action="{{ route('admin.badges.update', $badge->id) }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf
            @method('PUT')

            <!-- NAMA -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Badge</label>
                <input type="text" name="name"
                    value="{{ $badge->name }}"
                    class="w-full border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl p-3">
            </div>

            <!-- GRID -->
            <div class="grid grid-cols-2 gap-4">

                <!-- KATEGORI -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Kategori</label>
                    <input type="text" name="category"
                        value="{{ $badge->category }}"
                        class="w-full border border-slate-200 focus:border-emerald-500 rounded-xl p-3">
                </div>

                <!-- LEVEL -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Level</label>
                    <select name="level"
                        class="w-full border border-slate-200 focus:border-emerald-500 rounded-xl p-3">

                        <option {{ $badge->level == 'Common' ? 'selected' : '' }}>Common</option>
                        <option {{ $badge->level == 'Rare' ? 'selected' : '' }}>Rare</option>
                        <option {{ $badge->level == 'Epic' ? 'selected' : '' }}>Epic</option>
                        <option {{ $badge->level == 'Legendary' ? 'selected' : '' }}>Legendary</option>

                    </select>
                </div>

            </div>

            <!-- DESKRIPSI -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="3"
                    class="w-full border border-slate-200 focus:border-emerald-500 rounded-xl p-3">{{ $badge->description }}</textarea>
            </div>

            <!-- ICON -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Icon Badge</label>

                <!-- ICON LAMA -->
                @if($badge->icon)
                    <div class="mb-3 text-center">
                        <p class="text-xs text-slate-400 mb-1">Icon saat ini</p>
                        <img src="{{ asset('storage/'.$badge->icon) }}"
                             class="w-16 h-16 mx-auto rounded-full border shadow">
                    </div>
                @endif

                <!-- UPLOAD BARU -->
                <div class="border-2 border-dashed border-emerald-200 rounded-xl p-4 text-center hover:bg-emerald-50 transition cursor-pointer">
                    <input type="file" name="icon" class="hidden" onchange="previewImage(event)" id="upload">

                    <label for="upload" class="cursor-pointer text-sm text-slate-500 flex flex-col items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-500"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                        <span>Klik untuk ganti icon</span>
                    </label>

                    <img id="preview" class="mx-auto mt-3 w-16 h-16 hidden rounded-full border border-emerald-200 shadow"/>
                </div>
            </div>

            <!-- STATUS -->
            <div class="flex items-center gap-3 bg-emerald-50 p-3 rounded-xl">
                <input type="checkbox" name="is_active"
                    {{ $badge->is_active ? 'checked' : '' }}
                    class="w-4 h-4 text-emerald-600 rounded">
                <label class="text-sm text-emerald-700 font-medium">Badge aktif</label>
            </div>

            <!-- BUTTON -->
            <div class="flex justify-between items-center pt-4">
                <a href="{{ route('admin.badges.index') }}"
                   class="text-sm text-slate-500 hover:text-emerald-600 transition">
                    ← Kembali
                </a>

                <button class="bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white px-6 py-2.5 rounded-xl shadow-lg transition flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Update Badge
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
