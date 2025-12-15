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
        // Table is already dropped in the previous migration (drop_workout_routines_table)
        // This migration is a no-op to maintain migration history
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workout_routine_days', function (Blueprint $table) {
            //
        });
    }
};
