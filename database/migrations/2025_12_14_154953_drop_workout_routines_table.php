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
        // Drop the dependent table first to remove foreign key constraints
        Schema::dropIfExists('workout_routine_days');
        // Then drop the parent table
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
