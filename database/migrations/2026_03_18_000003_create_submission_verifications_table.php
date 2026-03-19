<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submission_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')
                  ->constrained('challenge_submissions')
                  ->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['verify', 'report']);
            $table->timestamp('created_at')->useCurrent();

            // Users can only verify OR report a submission once
            $table->unique(['submission_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submission_verifications');
    }
};
