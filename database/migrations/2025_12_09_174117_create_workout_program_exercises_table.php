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
        Schema::create('workout_program_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workout_program_id')->constrained()->onDelete('cascade');
            $table->foreignId('exercise_id')->constrained()->onDelete('cascade');
            $table->integer('order')->default(0); // Order in the workout
            $table->integer('sets')->default(3);
            $table->integer('reps')->nullable(); // Null for timed exercises
            $table->integer('duration_seconds')->nullable(); // For timed exercises
            $table->integer('rest_seconds')->default(60); // Rest between sets
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['workout_program_id', 'exercise_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workout_program_exercises');
    }
};
