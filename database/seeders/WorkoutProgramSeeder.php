<?php

namespace Database\Seeders;

use App\Models\Exercise;
use App\Models\WorkoutProgram;
use Illuminate\Database\Seeder;

class WorkoutProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get exercises
        $pushUps = Exercise::where('slug', 'push-ups')->first();
        $squats = Exercise::where('slug', 'squats')->first();
        $plank = Exercise::where('slug', 'plank')->first();
        $jumpingJacks = Exercise::where('slug', 'jumping-jacks')->first();
        $burpees = Exercise::where('slug', 'burpees')->first();
        $mountainClimbers = Exercise::where('slug', 'mountain-climbers')->first();
        $highKnees = Exercise::where('slug', 'high-knees')->first();
        $crunches = Exercise::where('slug', 'crunches')->first();
        $russianTwists = Exercise::where('slug', 'russian-twists')->first();
        $catCow = Exercise::where('slug', 'cat-cow-stretch')->first();

        // Beginner Full Body Workout
        $beginnerFullBody = WorkoutProgram::create([
            'name' => 'Beginner Full Body',
            'slug' => 'beginner-full-body',
            'description' => 'Perfect for those just starting their fitness journey. A balanced workout targeting all major muscle groups.',
            'level' => 'beginner',
            'goal' => 'toning',
            'duration_minutes' => 30,
            'days_per_week' => 3,
        ]);

        $beginnerFullBody->exercises()->attach([
            $pushUps->id => ['order' => 1, 'sets' => 3, 'reps' => 10, 'rest_seconds' => 60],
            $squats->id => ['order' => 2, 'sets' => 3, 'reps' => 12, 'rest_seconds' => 60],
            $plank->id => ['order' => 3, 'sets' => 3, 'duration_seconds' => 30, 'rest_seconds' => 45],
            $jumpingJacks->id => ['order' => 4, 'sets' => 2, 'duration_seconds' => 30, 'rest_seconds' => 30],
            $crunches->id => ['order' => 5, 'sets' => 3, 'reps' => 15, 'rest_seconds' => 45],
        ]);

        // Weight Loss HIIT Program
        $weightLossHIIT = WorkoutProgram::create([
            'name' => 'Fat Burner HIIT',
            'slug' => 'fat-burner-hiit',
            'description' => 'High-intensity interval training designed to maximize calorie burn and boost metabolism.',
            'level' => 'intermediate',
            'goal' => 'weight_loss',
            'duration_minutes' => 25,
            'days_per_week' => 4,
        ]);

        $weightLossHIIT->exercises()->attach([
            $burpees->id => ['order' => 1, 'sets' => 4, 'reps' => 10, 'rest_seconds' => 30],
            $mountainClimbers->id => ['order' => 2, 'sets' => 4, 'duration_seconds' => 30, 'rest_seconds' => 30],
            $highKnees->id => ['order' => 3, 'sets' => 4, 'duration_seconds' => 30, 'rest_seconds' => 30],
            $jumpingJacks->id => ['order' => 4, 'sets' => 4, 'duration_seconds' => 30, 'rest_seconds' => 30],
        ]);

        // Muscle Building Program
        $muscleBuilding = WorkoutProgram::create([
            'name' => 'Strength Builder',
            'slug' => 'strength-builder',
            'description' => 'Build muscle and increase strength with progressive resistance training.',
            'level' => 'intermediate',
            'goal' => 'muscle_building',
            'duration_minutes' => 45,
            'days_per_week' => 4,
        ]);

        $muscleBuilding->exercises()->attach([
            $pushUps->id => ['order' => 1, 'sets' => 4, 'reps' => 12, 'rest_seconds' => 90],
            $squats->id => ['order' => 2, 'sets' => 4, 'reps' => 15, 'rest_seconds' => 90],
            $plank->id => ['order' => 3, 'sets' => 3, 'duration_seconds' => 45, 'rest_seconds' => 60],
        ]);

        // Core Focus Program
        $coreFocus = WorkoutProgram::create([
            'name' => 'Core Strength',
            'slug' => 'core-strength',
            'description' => 'Targeted core workout for a strong and stable midsection.',
            'level' => 'beginner',
            'goal' => 'toning',
            'duration_minutes' => 20,
            'days_per_week' => 5,
        ]);

        $coreFocus->exercises()->attach([
            $plank->id => ['order' => 1, 'sets' => 3, 'duration_seconds' => 45, 'rest_seconds' => 30],
            $crunches->id => ['order' => 2, 'sets' => 3, 'reps' => 20, 'rest_seconds' => 30],
            $russianTwists->id => ['order' => 3, 'sets' => 3, 'reps' => 20, 'rest_seconds' => 30],
        ]);

        // Mobility & Flexibility Program
        $mobility = WorkoutProgram::create([
            'name' => 'Flexibility Flow',
            'slug' => 'flexibility-flow',
            'description' => 'Improve your range of motion and reduce muscle tension with mobility exercises.',
            'level' => 'beginner',
            'goal' => 'mobility_flexibility',
            'duration_minutes' => 15,
            'days_per_week' => 7,
        ]);

        $mobility->exercises()->attach([
            $catCow->id => ['order' => 1, 'sets' => 2, 'duration_seconds' => 60, 'rest_seconds' => 15],
        ]);

        // Advanced Athletic Performance
        $athleticPerformance = WorkoutProgram::create([
            'name' => 'Athlete Performance',
            'slug' => 'athlete-performance',
            'description' => 'Elite training program for peak athletic performance.',
            'level' => 'advanced',
            'goal' => 'athletic_performance',
            'duration_minutes' => 60,
            'days_per_week' => 5,
        ]);

        $athleticPerformance->exercises()->attach([
            $burpees->id => ['order' => 1, 'sets' => 5, 'reps' => 15, 'rest_seconds' => 45],
            $mountainClimbers->id => ['order' => 2, 'sets' => 5, 'duration_seconds' => 45, 'rest_seconds' => 30],
            $pushUps->id => ['order' => 3, 'sets' => 5, 'reps' => 20, 'rest_seconds' => 60],
            $squats->id => ['order' => 4, 'sets' => 5, 'reps' => 20, 'rest_seconds' => 60],
        ]);
    }
}
