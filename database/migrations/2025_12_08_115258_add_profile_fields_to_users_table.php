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
            $table->string('profile_photo')->nullable()->after('email');
            $table->string('fitness_goal')->nullable()->after('profile_photo');
            $table->enum('experience_level', ['beginner', 'intermediate', 'advanced', 'expert'])->nullable()->after('fitness_goal');
            $table->enum('subscription_tier', ['free', 'standard', 'pro'])->default('free')->after('experience_level');
            $table->json('device_capability')->nullable()->after('subscription_tier'); // Store device detection results
            $table->boolean('mediapipe_supported')->default(false)->after('device_capability');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'profile_photo',
                'fitness_goal',
                'experience_level',
                'subscription_tier',
                'device_capability',
                'mediapipe_supported',
            ]);
        });
    }
};
