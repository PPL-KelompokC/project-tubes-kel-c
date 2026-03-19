<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('pending_points')->default(0)->after('points');
            $table->integer('longest_streak')->default(0)->after('streak');
            $table->date('last_active_date')->nullable()->after('longest_streak');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['pending_points', 'longest_streak', 'last_active_date']);
        });
    }
};
