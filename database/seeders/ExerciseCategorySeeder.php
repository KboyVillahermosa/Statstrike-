<?php

namespace Database\Seeders;

use App\Models\ExerciseCategory;
use Illuminate\Database\Seeder;

class ExerciseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Strength',
                'slug' => 'strength',
                'description' => 'Build muscle and increase strength with resistance training exercises.',
                'icon' => '💪',
            ],
            [
                'name' => 'Cardio',
                'slug' => 'cardio',
                'description' => 'Improve cardiovascular health and endurance.',
                'icon' => '❤️',
            ],
            [
                'name' => 'HIIT',
                'slug' => 'hiit',
                'description' => 'High-intensity interval training for maximum calorie burn.',
                'icon' => '🔥',
            ],
            [
                'name' => 'Mobility',
                'slug' => 'mobility',
                'description' => 'Enhance flexibility and joint mobility.',
                'icon' => '🧘',
            ],
            [
                'name' => 'Core',
                'slug' => 'core',
                'description' => 'Strengthen your core muscles for better stability and posture.',
                'icon' => '🎯',
            ],
        ];

        foreach ($categories as $category) {
            ExerciseCategory::create($category);
        }
    }
}
