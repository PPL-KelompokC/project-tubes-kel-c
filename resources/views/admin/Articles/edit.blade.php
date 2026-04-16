@extends('admin.layouts.app')

@section('content')
<div class="p-6 max-w-3xl mx-auto">

    <h2 class="text-2xl font-bold mb-6">Edit Artikel</h2>

    <form action="{{ route('admin.articles.update', $article->id) }}"
          method="POST" enctype="multipart/form-data"
          class="bg-white p-6 rounded-2xl shadow space-y-5">
        @csrf
        @method('PUT')

        <!-- Judul -->
        <div>
            <label class="block mb-1 font-medium">Judul</label>
            <input type="text" name="title"
                   value="{{ $article->title }}"
                   class="w-full border p-3 rounded-lg"
                   required>
        </div>

        <!-- Kategori -->
        <div>
            <label class="block mb-1 font-medium">Kategori</label>
            <input type="text" name="category"
                   value="{{ $article->category }}"
                   class="w-full border p-3 rounded-lg">
        </div>

        <!-- Sinopsis -->
        <div>
            <label class="block mb-1 font-medium">Sinopsis</label>
            <textarea name="excerpt"
                      class="w-full border p-3 rounded-lg">{{ $article->excerpt }}</textarea>
        </div>

        <!-- Konten -->
        <div>
            <label class="block mb-1 font-medium">Konten</label>
            <textarea name="content"
                      class="w-full border p-3 rounded-lg"
                      rows="6"
                      required>{{ $article->content }}</textarea>
        </div>

        <!-- Thumbnail -->
        <div>
            <label class="block mb-1 font-medium">Thumbnail</label><br>

            @if($article->thumbnail)
                <img src="{{ asset('storage/'.$article->thumbnail) }}"
                     class="w-40 rounded-lg mb-3">
            @endif

            <input type="file" name="thumbnail"
                   class="w-full border p-2 rounded-lg"
                   onchange="previewImage(event)">

            <img id="preview" class="mt-3 w-40 rounded-lg hidden"/>
        </div>

        <!-- Publish --> 
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_published"
                   {{ $article->is_published ? 'checked' : '' }}>
            <label>Publish</label>
        </div>

        <!-- Button -->
        <div class="flex gap-3">
            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                Update
            </button>

            <a href="{{ route('admin.articles.index') }}"
               class="bg-gray-300 px-4 py-2 rounded-lg">
                Kembali
            </a>
        </div>

    </form>
</div>

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