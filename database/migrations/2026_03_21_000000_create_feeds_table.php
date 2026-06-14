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
        Schema::create('feeds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('caption');
            $table->json('media')->nullable(); // Store multiple media as JSON
            $table->enum('status', ['active', 'hidden'])->default('active');
            $table->integer('likes_count')->default(0);
            $table->integer('comments_count')->default(0);
            $table->string('feed_type')->default('post'); // post, badge_earned, challenge_complete, etc
            $table->timestamps();
            
            // Indexing for performance
            $table->index('user_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feeds');
    }
};
