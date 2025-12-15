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
        // Ensure the table exists for routines that depend on it
        if (! Schema::hasTable('workout_routine_days')) {
            Schema::create('workout_routine_days', function (Blueprint $table) {
                $table->id();
                $table->foreignId('workout_routine_id')->constrained()->cascadeOnDelete();
                $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
                $table->string('title');
                $table->text('description')->nullable();
                $table->text('exercises'); // JSON array of exercises
                $table->unsignedInteger('rounds')->default(5);
                $table->enum('intensity', ['easy', 'medium', 'hard'])->default('medium');
                $table->unsignedInteger('rest_minutes')->default(2);
                $table->json('tags')->nullable();
                $table->timestamps();

                $table->unique(['workout_routine_id', 'day_of_week']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workout_routine_days');
    }
};


