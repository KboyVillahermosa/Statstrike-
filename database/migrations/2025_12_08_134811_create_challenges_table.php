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
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['kicks', 'core', 'endurance', 'strength'])->default('kicks');
            $table->integer('target_count')->default(0); // Target number for the challenge (e.g., 500 kicks)
            $table->string('target_unit')->nullable(); // Unit of measurement (e.g., 'kicks', 'rounds', 'days')
            $table->integer('duration_days')->default(30); // Challenge duration in days
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->string('image_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenges');
    }
};
