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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('points')->default(0)->after('password');
            $table->integer('streak')->default(0)->after('points');
            $table->decimal('carbon_saved', 10, 2)->default(0)->after('streak');
            $table->integer('challenges_completed')->default(0)->after('carbon_saved');
            $table->string('avatar')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['points', 'streak', 'carbon_saved', 'challenges_completed', 'avatar']);
        });
    }
};
