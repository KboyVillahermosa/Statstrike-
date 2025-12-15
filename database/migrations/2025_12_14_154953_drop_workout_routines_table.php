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
        // Drop foreign key constraint from workout_routine_days first
        if (Schema::hasTable('workout_routine_days')) {
            Schema::table('workout_routine_days', function (Blueprint $table) {
                $table->dropForeign(['workout_routine_id']);
            });
            Schema::dropIfExists('workout_routine_days');
        }
        
        Schema::dropIfExists('workout_routines');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is for dropping the table, so down would recreate it
        // But since we have the proper migration, we'll leave this empty
    }
};
