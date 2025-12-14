<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WorkoutRoutine;
use App\Models\WorkoutRoutineDay;
use Illuminate\Database\Seeder;

class WorkoutRoutineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users or create for a specific user
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please create a user first.');
            return;
        }

        foreach ($users as $user) {
            // Check if user already has routines
            if ($user->workoutRoutines()->count() > 0) {
                $this->command->info("User {$user->name} already has routines. Skipping...");
                continue;
            }

            $this->createBoxingRoutine($user);
            $this->createMuayThaiRoutine($user);
            $this->createBJJRoutine($user);
            $this->createFullBodyRoutine($user);
        }
    }

    private function createBoxingRoutine(User $user): void
    {
        $routine = $user->workoutRoutines()->create([
            'name' => 'Basic Boxing Training',
            'description' => 'A comprehensive weekly boxing program for beginners to intermediate boxers. Focuses on fundamentals, conditioning, and technique.',
            'is_active' => true,
        ]);

        $routine->days()->createMany([
            [
                'day_of_week' => 'monday',
                'title' => 'Fundamentals & Shadow Boxing',
                'description' => 'Focus on basic techniques and form',
                'exercises' => [
                    '10 min warm-up (jump rope)',
                    '15 min shadow boxing (jab, cross, hooks)',
                    '5 rounds heavy bag (3 min rounds, 1 min rest)',
                    '3 rounds speed bag',
                    '10 min footwork drills',
                    '5 min cool down stretching'
                ],
                'rounds' => 5,
                'intensity' => 'medium',
                'rest_minutes' => 1,
                'tags' => ['Boxing', 'Fundamentals', 'Technique'],
            ],
            [
                'day_of_week' => 'tuesday',
                'title' => 'Cardio & Conditioning',
                'description' => 'Build endurance and stamina',
                'exercises' => [
                    '10 min warm-up jogging',
                    '5 rounds jump rope (3 min rounds)',
                    'Burpees: 3 sets of 10',
                    'Mountain climbers: 3 sets of 20',
                    'High knees: 3 sets of 30 seconds',
                    'Plank: 3 sets of 30 seconds',
                    '10 min cool down'
                ],
                'rounds' => 5,
                'intensity' => 'hard',
                'rest_minutes' => 1,
                'tags' => ['Cardio', 'Conditioning', 'Endurance'],
            ],
            [
                'day_of_week' => 'wednesday',
                'title' => 'Heavy Bag & Power Training',
                'description' => 'Develop power and technique on the bag',
                'exercises' => [
                    '10 min warm-up',
                    '6 rounds heavy bag (3 min rounds, 1 min rest)',
                    'Focus on power punches',
                    '3 rounds double-end bag',
                    'Push-ups: 3 sets of 15',
                    'Medicine ball slams: 3 sets of 10',
                    '5 min stretching'
                ],
                'rounds' => 6,
                'intensity' => 'hard',
                'rest_minutes' => 1,
                'tags' => ['Power', 'Heavy Bag', 'Strength'],
            ],
            [
                'day_of_week' => 'thursday',
                'title' => 'Technique & Speed Training',
                'description' => 'Improve speed and precision',
                'exercises' => [
                    '10 min warm-up',
                    '15 min shadow boxing (focus on speed)',
                    '5 rounds speed bag',
                    '5 rounds double-end bag',
                    'Pad work drills (if available)',
                    'Agility ladder drills: 10 min',
                    '5 min cool down'
                ],
                'rounds' => 5,
                'intensity' => 'medium',
                'rest_minutes' => 1,
                'tags' => ['Speed', 'Technique', 'Precision'],
            ],
            [
                'day_of_week' => 'friday',
                'title' => 'Full Body Conditioning',
                'description' => 'Total body strength and conditioning',
                'exercises' => [
                    '10 min warm-up',
                    '5 rounds heavy bag',
                    'Squats: 3 sets of 15',
                    'Lunges: 3 sets of 10 each leg',
                    'Pull-ups or lat pulldowns: 3 sets of 8',
                    'Core work: 3 rounds (crunches, leg raises)',
                    '10 min stretching'
                ],
                'rounds' => 5,
                'intensity' => 'hard',
                'rest_minutes' => 1,
                'tags' => ['Conditioning', 'Strength', 'Full Body'],
            ],
            [
                'day_of_week' => 'saturday',
                'title' => 'Sparring & Application',
                'description' => 'Apply techniques in controlled sparring',
                'exercises' => [
                    '15 min warm-up and stretching',
                    '3 rounds light sparring (if partner available)',
                    'OR 5 rounds shadow boxing with movement',
                    'Defensive drills: 10 min',
                    'Counter-punching practice: 10 min',
                    '5 rounds heavy bag (apply sparring techniques)',
                    '10 min cool down'
                ],
                'rounds' => 5,
                'intensity' => 'medium',
                'rest_minutes' => 2,
                'tags' => ['Sparring', 'Application', 'Defense'],
            ],
            [
                'day_of_week' => 'sunday',
                'title' => 'Active Recovery',
                'description' => 'Light movement and recovery',
                'exercises' => [
                    '20 min light jogging or walking',
                    '15 min stretching and mobility work',
                    'Foam rolling: 10 min',
                    'Light shadow boxing: 5 min',
                    'Meditation or breathing exercises: 5 min'
                ],
                'rounds' => 3,
                'intensity' => 'easy',
                'rest_minutes' => 1,
                'tags' => ['Recovery', 'Mobility', 'Rest'],
            ],
        ]);
    }

    private function createMuayThaiRoutine(User $user): void
    {
        $routine = $user->workoutRoutines()->create([
            'name' => 'Muay Thai Power Program',
            'description' => 'Develop explosive power in your kicks, knees, and elbows with this comprehensive Muay Thai training program.',
            'is_active' => false,
        ]);

        $routine->days()->createMany([
            [
                'day_of_week' => 'monday',
                'title' => 'Striking Fundamentals',
                'description' => 'Master the basics of Muay Thai striking',
                'exercises' => [
                    '10 min warm-up',
                    '15 min shadow boxing (all strikes)',
                    '5 rounds pad work (kicks, knees, elbows)',
                    '3 rounds heavy bag',
                    '5 min cool down'
                ],
                'rounds' => 5,
                'intensity' => 'medium',
                'rest_minutes' => 1,
                'tags' => ['Muay Thai', 'Striking', 'Fundamentals'],
            ],
            [
                'day_of_week' => 'tuesday',
                'title' => 'Clinch Work & Conditioning',
                'description' => 'Develop clinch techniques and conditioning',
                'exercises' => [
                    '10 min warm-up',
                    'Clinch drills: 20 min',
                    'Knee strikes on pads: 5 rounds',
                    'Core strengthening: 15 min',
                    '5 min stretching'
                ],
                'rounds' => 5,
                'intensity' => 'hard',
                'rest_minutes' => 1,
                'tags' => ['Clinch', 'Conditioning', 'Knees'],
            ],
            [
                'day_of_week' => 'wednesday',
                'title' => 'Kick Power Development',
                'description' => 'Focus on developing powerful kicks',
                'exercises' => [
                    '10 min warm-up',
                    'Roundhouse kick drills: 10 min',
                    '5 rounds heavy bag (kick focus)',
                    'Leg strength exercises: 15 min',
                    '5 min stretching'
                ],
                'rounds' => 5,
                'intensity' => 'hard',
                'rest_minutes' => 1,
                'tags' => ['Kicks', 'Power', 'Legs'],
            ],
            [
                'day_of_week' => 'thursday',
                'title' => 'Cardio & Endurance',
                'description' => 'Build cardiovascular endurance',
                'exercises' => [
                    '10 min warm-up',
                    '5 rounds jump rope',
                    'Running: 20 min',
                    'Burpees: 3 sets of 15',
                    '10 min cool down'
                ],
                'rounds' => 5,
                'intensity' => 'hard',
                'rest_minutes' => 1,
                'tags' => ['Cardio', 'Endurance', 'Conditioning'],
            ],
            [
                'day_of_week' => 'friday',
                'title' => 'Full Pad Work Session',
                'description' => 'Comprehensive pad work training',
                'exercises' => [
                    '10 min warm-up',
                    '6 rounds pad work (all techniques)',
                    '3 rounds heavy bag',
                    'Defensive drills: 10 min',
                    '5 min stretching'
                ],
                'rounds' => 6,
                'intensity' => 'hard',
                'rest_minutes' => 1,
                'tags' => ['Pad Work', 'Technique', 'Full Body'],
            ],
            [
                'day_of_week' => 'saturday',
                'title' => 'Sparring & Application',
                'description' => 'Apply techniques in sparring',
                'exercises' => [
                    '15 min warm-up',
                    'Light sparring: 3-5 rounds (if available)',
                    'OR technical sparring drills',
                    'Review and technique correction',
                    '10 min cool down'
                ],
                'rounds' => 5,
                'intensity' => 'medium',
                'rest_minutes' => 2,
                'tags' => ['Sparring', 'Application', 'Technique'],
            ],
            [
                'day_of_week' => 'sunday',
                'title' => 'Rest Day',
                'description' => 'Complete rest and recovery',
                'exercises' => [],
                'rounds' => 0,
                'intensity' => 'easy',
                'rest_minutes' => 0,
                'tags' => ['Rest', 'Recovery'],
            ],
        ]);
    }

    private function createBJJRoutine(User $user): void
    {
        $routine = $user->workoutRoutines()->create([
            'name' => 'BJJ Drilling Program',
            'description' => 'Sharpen your ground game with focused solo and partner drills for Brazilian Jiu-Jitsu.',
            'is_active' => false,
        ]);

        $routine->days()->createMany([
            [
                'day_of_week' => 'monday',
                'title' => 'Movement & Drills',
                'description' => 'Fundamental BJJ movements',
                'exercises' => [
                    '10 min warm-up',
                    'Shrimping drills: 5 min',
                    'Hip escape drills: 5 min',
                    'Rolling drills: 5 min',
                    'Guard passing drills: 10 min',
                    '5 min stretching'
                ],
                'rounds' => 4,
                'intensity' => 'easy',
                'rest_minutes' => 1,
                'tags' => ['BJJ', 'Movement', 'Drills'],
            ],
            [
                'day_of_week' => 'tuesday',
                'title' => 'Submission Chains',
                'description' => 'Practice submission combinations',
                'exercises' => [
                    '10 min warm-up',
                    'Submission chain drills: 20 min',
                    'Positional sparring: 15 min',
                    '5 min stretching'
                ],
                'rounds' => 4,
                'intensity' => 'medium',
                'rest_minutes' => 1,
                'tags' => ['Submissions', 'Chains', 'Technique'],
            ],
            [
                'day_of_week' => 'wednesday',
                'title' => 'Guard Passing Series',
                'description' => 'Focus on guard passing techniques',
                'exercises' => [
                    '10 min warm-up',
                    'Guard passing drills: 20 min',
                    'Guard retention drills: 15 min',
                    '5 min stretching'
                ],
                'rounds' => 4,
                'intensity' => 'medium',
                'rest_minutes' => 1,
                'tags' => ['Guard', 'Passing', 'Technique'],
            ],
            [
                'day_of_week' => 'thursday',
                'title' => 'Positional Sparring',
                'description' => 'Practice from specific positions',
                'exercises' => [
                    '10 min warm-up',
                    'Positional sparring: 30 min',
                    'Sweep drills: 10 min',
                    '5 min stretching'
                ],
                'rounds' => 5,
                'intensity' => 'medium',
                'rest_minutes' => 1,
                'tags' => ['Sparring', 'Positional', 'Application'],
            ],
            [
                'day_of_week' => 'friday',
                'title' => 'Full Rolling Session',
                'description' => 'Complete rolling practice',
                'exercises' => [
                    '15 min warm-up',
                    'Full rolling: 5 rounds (5 min each)',
                    'Technique review: 10 min',
                    '10 min stretching'
                ],
                'rounds' => 5,
                'intensity' => 'hard',
                'rest_minutes' => 2,
                'tags' => ['Rolling', 'Full Session', 'Application'],
            ],
            [
                'day_of_week' => 'saturday',
                'title' => 'Strength & Conditioning',
                'description' => 'BJJ-specific strength training',
                'exercises' => [
                    '10 min warm-up',
                    'Grip strength exercises: 15 min',
                    'Core work: 15 min',
                    'Functional strength: 15 min',
                    '10 min stretching'
                ],
                'rounds' => 4,
                'intensity' => 'medium',
                'rest_minutes' => 1,
                'tags' => ['Strength', 'Conditioning', 'BJJ'],
            ],
            [
                'day_of_week' => 'sunday',
                'title' => 'Rest Day',
                'description' => 'Complete rest and recovery',
                'exercises' => [],
                'rounds' => 0,
                'intensity' => 'easy',
                'rest_minutes' => 0,
                'tags' => ['Rest', 'Recovery'],
            ],
        ]);
    }

    private function createFullBodyRoutine(User $user): void
    {
        $routine = $user->workoutRoutines()->create([
            'name' => 'Full Body Fitness',
            'description' => 'A balanced full-body workout routine focusing on strength, cardio, and flexibility.',
            'is_active' => false,
        ]);

        $routine->days()->createMany([
            [
                'day_of_week' => 'monday',
                'title' => 'Upper Body Strength',
                'description' => 'Focus on upper body muscles',
                'exercises' => [
                    '10 min warm-up',
                    'Push-ups: 3 sets of 15',
                    'Pull-ups: 3 sets of 8',
                    'Dumbbell rows: 3 sets of 12',
                    'Shoulder press: 3 sets of 10',
                    '10 min stretching'
                ],
                'rounds' => 3,
                'intensity' => 'medium',
                'rest_minutes' => 2,
                'tags' => ['Strength', 'Upper Body', 'Muscle'],
            ],
            [
                'day_of_week' => 'tuesday',
                'title' => 'Cardio & HIIT',
                'description' => 'High-intensity cardio workout',
                'exercises' => [
                    '10 min warm-up',
                    'HIIT circuit: 20 min',
                    'Burpees: 3 sets of 10',
                    'Mountain climbers: 3 sets of 20',
                    'Jumping jacks: 3 sets of 30',
                    '10 min cool down'
                ],
                'rounds' => 5,
                'intensity' => 'hard',
                'rest_minutes' => 1,
                'tags' => ['Cardio', 'HIIT', 'Endurance'],
            ],
            [
                'day_of_week' => 'wednesday',
                'title' => 'Lower Body Strength',
                'description' => 'Focus on lower body muscles',
                'exercises' => [
                    '10 min warm-up',
                    'Squats: 3 sets of 15',
                    'Lunges: 3 sets of 10 each leg',
                    'Deadlifts: 3 sets of 10',
                    'Calf raises: 3 sets of 15',
                    '10 min stretching'
                ],
                'rounds' => 3,
                'intensity' => 'medium',
                'rest_minutes' => 2,
                'tags' => ['Strength', 'Lower Body', 'Legs'],
            ],
            [
                'day_of_week' => 'thursday',
                'title' => 'Core & Flexibility',
                'description' => 'Core strength and flexibility training',
                'exercises' => [
                    '10 min warm-up',
                    'Plank: 3 sets of 45 seconds',
                    'Crunches: 3 sets of 20',
                    'Leg raises: 3 sets of 15',
                    'Yoga flow: 15 min',
                    '10 min stretching'
                ],
                'rounds' => 3,
                'intensity' => 'easy',
                'rest_minutes' => 1,
                'tags' => ['Core', 'Flexibility', 'Yoga'],
            ],
            [
                'day_of_week' => 'friday',
                'title' => 'Full Body Circuit',
                'description' => 'Complete full-body workout',
                'exercises' => [
                    '10 min warm-up',
                    'Full body circuit: 30 min',
                    'Combination exercises',
                    'Functional movements',
                    '10 min stretching'
                ],
                'rounds' => 5,
                'intensity' => 'hard',
                'rest_minutes' => 1,
                'tags' => ['Full Body', 'Circuit', 'Functional'],
            ],
            [
                'day_of_week' => 'saturday',
                'title' => 'Active Recovery',
                'description' => 'Light activity and mobility',
                'exercises' => [
                    '20 min light walking',
                    '15 min stretching',
                    'Foam rolling: 10 min',
                    'Mobility exercises: 10 min'
                ],
                'rounds' => 3,
                'intensity' => 'easy',
                'rest_minutes' => 1,
                'tags' => ['Recovery', 'Mobility', 'Active'],
            ],
            [
                'day_of_week' => 'sunday',
                'title' => 'Rest Day',
                'description' => 'Complete rest',
                'exercises' => [],
                'rounds' => 0,
                'intensity' => 'easy',
                'rest_minutes' => 0,
                'tags' => ['Rest', 'Recovery'],
            ],
        ]);
    }
}
