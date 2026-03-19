<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('challenge_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('challenge_id')->constrained()->onDelete('cascade');
            $table->string('photo_path');
            $table->timestamp('exif_timestamp')->nullable();
            $table->decimal('exif_lat', 10, 7)->nullable();
            $table->decimal('exif_lng', 10, 7)->nullable();
            $table->integer('ai_score')->nullable();
            $table->json('ai_labels')->nullable();
            $table->enum('status', [
                'pending_ai',
                'pending_community',
                'verified',
                'rejected',
                'manual_review',
            ])->default('pending_ai');
            $table->integer('points_awarded')->default(0);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            // One submission per user per challenge per day
            $table->unique(['user_id', 'challenge_id', 'created_at'], 'unique_daily_submission');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('challenge_submissions');
    }
};
