<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('challenges', function (Blueprint $table) {
            $table->json('ai_keywords')->nullable()->after('image_url');
            $table->boolean('is_daily')->default(false)->after('ai_keywords');
            $table->date('active_date')->nullable()->after('is_daily');
        });
    }

    public function down(): void
    {
        Schema::table('challenges', function (Blueprint $table) {
            $table->dropColumn(['ai_keywords', 'is_daily', 'active_date']);
        });
    }
};
