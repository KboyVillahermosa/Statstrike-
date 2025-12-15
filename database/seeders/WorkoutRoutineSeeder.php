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
            'name' => 'Boxing Conditioning Library',
            'description' => 'A comprehensive weekly boxing conditioning program with detailed warm-ups, circuits, and finishers for optimal performance.',
            'is_active' => true,
        ]);

        $routine->days()->createMany([
            [
                'day_of_week' => 'monday',
                'title' => 'Boxing Conditioning A',
                'description' => 'Fundamentals with warm-up, main circuit, and finisher',
                'exercises' => [
                    'WARM-UP (5–7 min):',
                    '• Jumping jacks – 1 min',
                    '• Arm circles – 30s forward, 30s backward',
                    '• Hip rotations – 1 min',
                    '• Dynamic stretches – leg swings, torso twists (1 min)',
                    '• In-place footwork shuffle – 1 min',
                    '',
                    'MAIN CIRCUIT (Repeat 3×):',
                    '1. Shadow Boxing – 2 min (Focus on jabs, crosses, guard up, light combos)',
                    '2. Push-ups – 45s (Build upper-body endurance for punching)',
                    '3. Squat to Alternating Knee – 45s (Develop explosive movement and balance)',
                    '4. Plank – 45s',
                    '',
                    'Rest – 1 minute between circuits',
                    '',
                    'FINISHER:',
                    '• Fast straights (air punches) – 30s',
                    '• Slow-controlled combos – 30s'
                ],
                'rounds' => 3,
                'intensity' => 'medium',
                'rest_minutes' => 1,
                'tags' => ['Boxing', 'Conditioning', 'Fundamentals'],
            ],
            [
                'day_of_week' => 'tuesday',
                'title' => 'Recovery / Light Mobility',
                'description' => 'Active recovery day with light movement',
                'exercises' => [
                    '• Light movement – 15 min',
                    '• Gentle stretching – 15 min',
                    '• Foam rolling – 10 min'
                ],
                'rounds' => 2,
                'intensity' => 'easy',
                'rest_minutes' => 1,
                'tags' => ['Recovery', 'Mobility', 'Rest'],
            ],
            [
                'day_of_week' => 'wednesday',
                'title' => 'Boxing Conditioning B',
                'description' => 'Advanced boxing with head movement focus',
                'exercises' => [
                    'WARM-UP (5–7 min):',
                    '• Same as Day 1',
                    '',
                    'MAIN CIRCUIT (Repeat 3×):',
                    '1. Shadow Boxing (Head Movement Focus) – 2 min (Slip left/right, roll under hooks)',
                    '2. Burpees – 30s (Anaerobic explosion for ring conditioning)',
                    '3. Reverse lunges – 45s',
                    '4. Russian twists – 45s',
                    '',
                    'Rest – 1 min',
                    '',
                    'FINISHER:',
                    '• 1 min nonstop jab (light speed)'
                ],
                'rounds' => 3,
                'intensity' => 'hard',
                'rest_minutes' => 1,
                'tags' => ['Boxing', 'Head Movement', 'Conditioning'],
            ],
            [
                'day_of_week' => 'thursday',
                'title' => 'Core & Footwork Burner',
                'description' => 'Core strength and footwork emphasis',
                'exercises' => [
                    'WARM-UP:',
                    '• Light skip + dynamic stretch',
                    '',
                    'MAIN CIRCUIT (Repeat 2×):',
                    '1. Lateral footwork line drill – 1 min (Step left/right, stay light on toes)',
                    '2. Forward-backward footwork – 1 min',
                    '',
                    '3. Core circuit:',
                    '   • Plank – 45s',
                    '   • Leg raises – 15 reps',
                    '   • Flutter kicks – 30s',
                    '',
                    'Rest – 1 min'
                ],
                'rounds' => 2,
                'intensity' => 'hard',
                'rest_minutes' => 1,
                'tags' => ['Core', 'Footwork', 'Balance'],
            ],
            [
                'day_of_week' => 'friday',
                'title' => 'Active Recovery',
                'description' => 'Light activity and movement',
                'exercises' => [
                    '• Light walk or easy movement – 15 min',
                    '• Gentle stretching – 15 min',
                    '• Foam rolling – 10 min'
                ],
                'rounds' => 2,
                'intensity' => 'easy',
                'rest_minutes' => 1,
                'tags' => ['Recovery', 'Active', 'Mobility'],
            ],
            [
                'day_of_week' => 'saturday',
                'title' => 'Boxing Conditioning C',
                'description' => 'Combinations and defense focus',
                'exercises' => [
                    'WARM-UP:',
                    '• Basic mobility + shadow boxing',
                    '',
                    'MAIN CIRCUIT (Repeat 3×):',
                    '1. Shadow Boxing (Combos + Defense) – 3 min (1–2, slip, 1–2–3, roll)',
                    '2. Mountain climbers – 45s',
                    '3. Jump squats – 30s',
                    '4. Hollow hold – 30s',
                    '',
                    'Rest – 1 min'
                ],
                'rounds' => 3,
                'intensity' => 'hard',
                'rest_minutes' => 1,
                'tags' => ['Boxing', 'Combinations', 'Defense'],
            ],
            [
                'day_of_week' => 'sunday',
                'title' => 'Full Rest',
                'description' => 'Complete rest day',
                'exercises' => [],
                'rounds' => 0,
                'intensity' => 'easy',
                'rest_minutes' => 0,
                'tags' => ['Rest', 'Recovery'],
            ],
        ]);
    }



    private function createMuayThaiRoutine(User $user): void
    {
        $routine = $user->workoutRoutines()->create([
            'name' => 'Muay Thai Conditioning Library',
            'description' => 'Complete Muay Thai conditioning program with detailed warm-ups, circuits, and clinch training for optimal performance.',
            'is_active' => false,
        ]);

        $routine->days()->createMany([
            [
                'day_of_week' => 'monday',
                'title' => 'MT Conditioning A',
                'description' => 'Fundamental Muay Thai with warm-up and circuit',
                'exercises' => [
                    'WARM-UP (5–7 min):',
                    '• High knees – 1 min',
                    '• Butt kicks – 1 min',
                    '• Hip-opening stretches – 1 min',
                    '• Knee raise steps – 1 min',
                    '• Light shadow Muay Thai – 1 min',
                    '',
                    'MAIN CIRCUIT (Repeat 3×):',
                    '1. Shadow Muay Thai – 2 min (Focus: jabs, crosses, teeps, light roundhouse motions)',
                    '2. Knee strikes (air) – 45s (Drive hips, switch knees every 2 reps)',
                    '3. Squats – 45s (Power base for kicks and teeps)',
                    '4. Plank – 45s',
                    '',
                    'Rest – 1 minute'
                ],
                'rounds' => 3,
                'intensity' => 'medium',
                'rest_minutes' => 1,
                'tags' => ['Muay Thai', 'Conditioning', 'Fundamentals'],
            ],
            [
                'day_of_week' => 'tuesday',
                'title' => 'Active Recovery / Mobility',
                'description' => 'Light movement and mobility work',
                'exercises' => [
                    '• Light stretching – 15 min',
                    '• Mobility exercises – 15 min',
                    '• Gentle movement – 10 min'
                ],
                'rounds' => 2,
                'intensity' => 'easy',
                'rest_minutes' => 1,
                'tags' => ['Recovery', 'Mobility', 'Active'],
            ],
            [
                'day_of_week' => 'wednesday',
                'title' => 'MT Conditioning B',
                'description' => 'Elbows focus with dynamic mobility',
                'exercises' => [
                    'WARM-UP:',
                    '• Dynamic mobility with light teep motions',
                    '',
                    'MAIN CIRCUIT (Repeat 3×):',
                    '1. Shadow Muay Thai (Elbows Focus) – 2 min (Horizontal, upward, diagonal elbows)',
                    '2. Lunge-to-knee combo – 10 each leg (Explosive knee mechanics)',
                    '3. Sprawls – 30s (For overall fight conditioning)',
                    '4. Side planks – 30s each side',
                    '',
                    'Rest – 1 min'
                ],
                'rounds' => 3,
                'intensity' => 'hard',
                'rest_minutes' => 1,
                'tags' => ['Muay Thai', 'Elbows', 'Conditioning'],
            ],
            [
                'day_of_week' => 'thursday',
                'title' => 'Clinch & Balance Drills',
                'description' => 'Balance and clinch control training',
                'exercises' => [
                    'MAIN CIRCUIT (Repeat 2×):',
                    '1. Single-leg balance with knee chamber – 45s each leg (Develops stability for kicks)',
                    '2. Hip rotation drills – 1 min (Simulate roundhouse mechanics)',
                    '',
                    '3. Core circuit:',
                    '   • Seated knee tucks – 30s',
                    '   • Plank with reach – 30s'
                ],
                'rounds' => 2,
                'intensity' => 'medium',
                'rest_minutes' => 1,
                'tags' => ['Clinch', 'Balance', 'Stability'],
            ],
            [
                'day_of_week' => 'friday',
                'title' => 'Recovery',
                'description' => 'Complete recovery day',
                'exercises' => [
                    '• Gentle stretching – 20 min',
                    '• Light movement – 15 min',
                    '• Breathing exercises – 10 min'
                ],
                'rounds' => 2,
                'intensity' => 'easy',
                'rest_minutes' => 1,
                'tags' => ['Recovery', 'Rest', 'Relaxation'],
            ],
            [
                'day_of_week' => 'saturday',
                'title' => 'MT Conditioning C',
                'description' => 'Combinations and flow training',
                'exercises' => [
                    'WARM-UP:',
                    '• Light Muay Thai flow',
                    '',
                    'MAIN CIRCUIT (Repeat 3×):',
                    '1. Shadow Muay Thai (Combinations) – 3 min (Punch-kick-knee-elbow flow)',
                    '2. Fast teeps (air) – 45s',
                    '3. Jump lunges – 30s',
                    '4. Bicycle crunches – 45s',
                    '',
                    'Rest – 1 min'
                ],
                'rounds' => 3,
                'intensity' => 'hard',
                'rest_minutes' => 1,
                'tags' => ['Muay Thai', 'Combinations', 'Flow'],
            ],
            [
                'day_of_week' => 'sunday',
                'title' => 'Rest',
                'description' => 'Complete rest day',
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
            'name' => 'Total Body Conditioning Library',
            'description' => 'Comprehensive total body conditioning program with structured warm-ups, circuits, and finishers for overall fitness.',
            'is_active' => false,
        ]);

        $routine->days()->createMany([
            [
                'day_of_week' => 'monday',
                'title' => 'Total Body A',
                'description' => 'Full body workout with warm-up and circuit',
                'exercises' => [
                    'WARM-UP (5 min):',
                    '• Jog in place – 1 min',
                    '• Dynamic leg swings – 1 min',
                    '• Hip circles – 1 min',
                    '• Arm swings – 1 min',
                    '• Light full-body movement – 1 min',
                    '',
                    'MAIN CIRCUIT (Repeat 3×):',
                    '1. Push-ups – 45s',
                    '2. Air squats – 45s',
                    '3. Plank shoulder taps – 45s',
                    '4. High knees – 30s',
                    '',
                    'Rest – 1 min'
                ],
                'rounds' => 3,
                'intensity' => 'medium',
                'rest_minutes' => 1,
                'tags' => ['Total Body', 'Strength', 'Conditioning'],
            ],
            [
                'day_of_week' => 'tuesday',
                'title' => 'Mobility / Light Stretching',
                'description' => 'Recovery and mobility focus',
                'exercises' => [
                    '• Gentle stretching – 20 min',
                    '• Mobility exercises – 15 min',
                    '• Light movement – 10 min'
                ],
                'rounds' => 2,
                'intensity' => 'easy',
                'rest_minutes' => 1,
                'tags' => ['Mobility', 'Stretching', 'Recovery'],
            ],
            [
                'day_of_week' => 'wednesday',
                'title' => 'Total Body B',
                'description' => 'Advanced full body conditioning',
                'exercises' => [
                    'MAIN CIRCUIT (Repeat 3×):',
                    '1. Lunges – 45s',
                    '2. Mountain climbers – 45s',
                    '3. Sit-ups – 45s',
                    '4. Fast feet – 30s'
                ],
                'rounds' => 3,
                'intensity' => 'hard',
                'rest_minutes' => 1,
                'tags' => ['Total Body', 'Cardio', 'Strength'],
            ],
            [
                'day_of_week' => 'thursday',
                'title' => 'Core & Agility',
                'description' => 'Core strength and agility training',
                'exercises' => [
                    'CIRCUIT (Repeat 2×):',
                    '1. Lateral shuffle – 1 min',
                    '2. Plank – 1 min',
                    '3. Bicycle crunches – 45s',
                    '4. Burpees – 30s'
                ],
                'rounds' => 2,
                'intensity' => 'medium',
                'rest_minutes' => 1,
                'tags' => ['Core', 'Agility', 'Conditioning'],
            ],
            [
                'day_of_week' => 'friday',
                'title' => 'Recovery',
                'description' => 'Complete recovery day',
                'exercises' => [
                    '• Gentle stretching – 20 min',
                    '• Light walking – 15 min',
                    '• Breathing exercises – 10 min'
                ],
                'rounds' => 2,
                'intensity' => 'easy',
                'rest_minutes' => 1,
                'tags' => ['Recovery', 'Rest', 'Relaxation'],
            ],
            [
                'day_of_week' => 'saturday',
                'title' => 'Total Body C',
                'description' => 'Final total body conditioning session',
                'exercises' => [
                    'MAIN CIRCUIT (Repeat 3×):',
                    '1. Wide push-ups – 45s',
                    '2. Squat pulses – 30s',
                    '3. Reverse crunches – 45s',
                    '4. Skaters – 45s'
                ],
                'rounds' => 3,
                'intensity' => 'hard',
                'rest_minutes' => 1,
                'tags' => ['Total Body', 'Final', 'Challenge'],
            ],
            [
                'day_of_week' => 'sunday',
                'title' => 'Rest',
                'description' => 'Complete rest day',
                'exercises' => [],
                'rounds' => 0,
                'intensity' => 'easy',
                'rest_minutes' => 0,
                'tags' => ['Rest', 'Recovery'],
            ],
        ]);
    }
}
