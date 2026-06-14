<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('title'); // Judul artikel
            $table->string('slug')->unique(); // URL yang ramah SEO
            $table->text('excerpt')->nullable(); // Ringkasan artikel
            $table->longText('content'); // Isi lengkap artikel
            $table->string('thumbnail')->nullable(); // Path gambar
            $table->string('category')->nullable(); // Kategori artikel
            $table->boolean('is_published')->default(true); // Status publish
            $table->foreignId('author_id') // Relasi ke tabel users
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
