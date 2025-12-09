<?php

namespace Database\Seeders;

use App\Models\Challenge;
use Illuminate\Database\Seeder;

class ChallengeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $challenges = [
            [
                'title' => '500 Kicks in a Month',
                'description' => 'Test your endurance and technique by landing 500 kicks this month.',
                'type' => 'kicks',
                'target_count' => 500,
                'target_unit' => 'kicks',
                'duration_days' => 30,
                'is_active' => true,
                'starts_at' => now()->startOfMonth(),
                'ends_at' => now()->endOfMonth(),
            ],
            [
                'title' => 'Core Crusher',
                'description' => 'Strengthen your core with 30 consecutive days of ab workouts.',
                'type' => 'core',
                'target_count' => 30,
                'target_unit' => 'days',
                'duration_days' => 30,
                'is_active' => true,
                'starts_at' => now()->startOfMonth(),
                'ends_at' => now()->endOfMonth(),
            ],
            [
                'title' => 'Endurance Master',
                'description' => 'Build your stamina with 100 rounds of high-intensity training this month.',
                'type' => 'endurance',
                'target_count' => 100,
                'target_unit' => 'rounds',
                'duration_days' => 30,
                'is_active' => true,
                'starts_at' => now()->startOfMonth(),
                'ends_at' => now()->endOfMonth(),
            ],
        ];

        foreach ($challenges as $challenge) {
            Challenge::create($challenge);
        }
    }
}
