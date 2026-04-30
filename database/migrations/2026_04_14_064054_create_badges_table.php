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
        Schema::create('badges', function (Blueprint $table) {
            $table->id();

            // Nama badge (contoh: Eco Starter)
            $table->string('name');

            // Icon badge (disimpan path gambar)
            $table->string('icon')->nullable();

            // Kategori badge (milestone, streak, dll)
            $table->string('category')->nullable();

            // Level badge (Common, Rare, Epic, Legendary)
            $table->string('level');

            // Deskripsi badge
            $table->text('description')->nullable();

            // Status aktif / tidak
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};