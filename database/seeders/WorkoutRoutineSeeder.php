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
            $this->createBeginnerNoEquipmentRoutine($user);
        }
    }

    private function createBoxingRoutine(User $user): void
    {
        $routine = $user->workoutRoutines()->create([
            'name' => 'Boxing Circuit Training',
            'description' => 'High-intensity boxing circuit training program. Each day features timed circuits combining boxing techniques with conditioning exercises for maximum fat burn and skill development.',
            'is_active' => true,
        ]);

        $routine->days()->createMany([
            [
                'day_of_week' => 'monday',
                'title' => 'Boxing Fundamentals Circuit',
                'description' => 'Master basic punches through high-intensity circuits',
                'exercises' => [
                    '3 min warm-up (jump rope or shadow boxing)',
                    'Circuit (45 sec work/15 sec rest x 4 rounds):',
                    'Jab-Cross combinations: 45 sec',
                    'Push-ups: 45 sec',
                    'Hook combinations: 45 sec',
                    'Burpees: 45 sec',
                    'Uppercut combinations: 45 sec',
                    'Mountain climbers: 45 sec',
                    '2 min cool down stretching'
                ],
                'rounds' => 4,
                'intensity' => 'hard',
                'rest_minutes' => 1,
                'tags' => ['Boxing', 'Circuit', 'Fundamentals', 'HIIT'],
            ],
            [
                'day_of_week' => 'tuesday',
                'title' => 'Cardio Boxing Circuit',
                'description' => 'Explosive cardio-focused boxing circuits',
                'exercises' => [
                    '3 min warm-up (high knees, arm circles)',
                    'Circuit (40 sec work/20 sec rest x 5 rounds):',
                    'Shadow boxing combinations: 40 sec',
                    'High knees: 40 sec',
                    'Heavy bag work (if available): 40 sec',
                    'Jumping jacks: 40 sec',
                    'Speed bag (if available): 40 sec',
                    'Butt kicks: 40 sec',
                    '2 min cool down'
                ],
                'rounds' => 5,
                'intensity' => 'hard',
                'rest_minutes' => 1,
                'tags' => ['Cardio', 'Boxing', 'Circuit', 'Conditioning'],
            ],
            [
                'day_of_week' => 'wednesday',
                'title' => 'Power & Strength Circuit',
                'description' => 'Build explosive power with boxing strength circuits',
                'exercises' => [
                    '3 min warm-up (light shadow boxing)',
                    'Circuit (50 sec work/10 sec rest x 4 rounds):',
                    'Power punches on heavy bag: 50 sec',
                    'Push-ups: 50 sec',
                    'Medicine ball slams (or squat jumps): 50 sec',
                    'Plank punches: 50 sec',
                    'Squat to punch: 50 sec',
                    'Russian twists: 50 sec',
                    '2 min cool down stretching'
                ],
                'rounds' => 4,
                'intensity' => 'hard',
                'rest_minutes' => 1,
                'tags' => ['Power', 'Strength', 'Boxing', 'Circuit'],
            ],
            [
                'day_of_week' => 'thursday',
                'title' => 'Speed & Agility Circuit',
                'description' => 'Lightning-fast boxing speed and footwork circuits',
                'exercises' => [
                    '3 min warm-up (footwork drills)',
                    'Circuit (30 sec work/30 sec rest x 6 rounds):',
                    'Speed punches (jab-jab-cross): 30 sec',
                    'Footwork ladder (imaginary): 30 sec',
                    'Double-end bag (if available): 30 sec',
                    'Agility dots (imaginary): 30 sec',
                    'Pad combinations: 30 sec',
                    'Reaction ball (or shadow): 30 sec',
                    '2 min cool down'
                ],
                'rounds' => 6,
                'intensity' => 'hard',
                'rest_minutes' => 1,
                'tags' => ['Speed', 'Agility', 'Boxing', 'Footwork'],
            ],
            [
                'day_of_week' => 'friday',
                'title' => 'Full Body Boxing Circuit',
                'description' => 'Complete boxing conditioning circuit',
                'exercises' => [
                    '3 min warm-up (full body movement)',
                    'Circuit (45 sec work/15 sec rest x 5 rounds):',
                    'Heavy bag combinations: 45 sec',
                    'Squats with punches: 45 sec',
                    'Shadow boxing with movement: 45 sec',
                    'Lunges with hooks: 45 sec',
                    'Core punches (plank position): 45 sec',
                    'Burpee with punch: 45 sec',
                    '2 min cool down stretching'
                ],
                'rounds' => 5,
                'intensity' => 'hard',
                'rest_minutes' => 1,
                'tags' => ['Full Body', 'Boxing', 'Circuit', 'Conditioning'],
            ],
            [
                'day_of_week' => 'saturday',
                'title' => 'Sparring Circuit & Application',
                'description' => 'Apply techniques in circuit format',
                'exercises' => [
                    '5 min warm-up and stretching',
                    'Circuit (1 min work/30 sec rest x 4 rounds):',
                    'Light sparring (if partner): 1 min',
                    'OR Shadow boxing defense: 1 min',
                    'Counter-punching drills: 1 min',
                    'Footwork under pressure: 1 min',
                    'Combination practice: 1 min',
                    'Heavy bag application: 1 min',
                    '3 min cool down'
                ],
                'rounds' => 4,
                'intensity' => 'medium',
                'rest_minutes' => 1,
                'tags' => ['Sparring', 'Application', 'Boxing', 'Circuit'],
            ],
            [
                'day_of_week' => 'sunday',
                'title' => 'Active Recovery Circuit',
                'description' => 'Light recovery with mobility circuits',
                'exercises' => [
                    '5 min gentle warm-up',
                    'Recovery Circuit (1 min work/2 min rest x 3 rounds):',
                    'Light shadow boxing: 1 min',
                    'Mobility stretches: 1 min',
                    'Deep breathing: 1 min',
                    'Gentle footwork: 1 min',
                    'Meditation: 1 min',
                    'Light walking: 1 min'
                ],
                'rounds' => 3,
                'intensity' => 'easy',
                'rest_minutes' => 2,
                'tags' => ['Recovery', 'Mobility', 'Boxing', 'Light'],
            ],
        ]);
    }

    private function createMuayThaiRoutine(User $user): void
    {
        $routine = $user->workoutRoutines()->create([
            'name' => 'Muay Thai Circuit Training',
            'description' => 'High-intensity Muay Thai circuit training combining strikes, clinch work, and conditioning for explosive power and endurance.',
            'is_active' => false,
        ]);

        $routine->days()->createMany([
            [
                'day_of_week' => 'monday',
                'title' => 'Muay Thai Fundamentals Circuit',
                'description' => 'Master basic Muay Thai strikes through intense circuits',
                'exercises' => [
                    '3 min warm-up (shadow boxing)',
                    'Circuit (45 sec work/15 sec rest x 4 rounds):',
                    'Jab-cross-hook combinations: 45 sec',
                    'Roundhouse kicks: 45 sec',
                    'Push-ups: 45 sec',
                    'Knee strikes (on air): 45 sec',
                    'Elbow combinations: 45 sec',
                    'Burpees: 45 sec',
                    '2 min cool down stretching'
                ],
                'rounds' => 4,
                'intensity' => 'hard',
                'rest_minutes' => 1,
                'tags' => ['Muay Thai', 'Circuit', 'Fundamentals', 'HIIT'],
            ],
            [
                'day_of_week' => 'tuesday',
                'title' => 'Clinch & Knee Circuit',
                'description' => 'Explosive clinch work and knee strikes in circuit format',
                'exercises' => [
                    '3 min warm-up (arm circles, torso twists)',
                    'Circuit (40 sec work/20 sec rest x 5 rounds):',
                    'Clinch entries and control: 40 sec',
                    'Knee strikes: 40 sec',
                    'Russian twists: 40 sec',
                    'Plank with shoulder taps: 40 sec',
                    'Shadow clinch work: 40 sec',
                    'Mountain climbers: 40 sec',
                    '2 min cool down'
                ],
                'rounds' => 5,
                'intensity' => 'hard',
                'rest_minutes' => 1,
                'tags' => ['Clinch', 'Knees', 'Muay Thai', 'Circuit'],
            ],
            [
                'day_of_week' => 'wednesday',
                'title' => 'Kick Power Circuit',
                'description' => 'Develop explosive kicking power through circuits',
                'exercises' => [
                    '3 min warm-up (light kicks)',
                    'Circuit (50 sec work/10 sec rest x 4 rounds):',
                    'Roundhouse kicks: 50 sec',
                    'Push kicks: 50 sec',
                    'Squats with kick: 50 sec',
                    'Lunges with kick: 50 sec',
                    'Calf raises: 50 sec',
                    'High knees: 50 sec',
                    '2 min cool down stretching'
                ],
                'rounds' => 4,
                'intensity' => 'hard',
                'rest_minutes' => 1,
                'tags' => ['Kicks', 'Power', 'Muay Thai', 'Circuit'],
            ],
            [
                'day_of_week' => 'thursday',
                'title' => 'Cardio Strike Circuit',
                'description' => 'High-intensity cardio with Muay Thai striking',
                'exercises' => [
                    '3 min warm-up (jump rope or jogging)',
                    'Circuit (30 sec work/30 sec rest x 6 rounds):',
                    'Full striking combinations: 30 sec',
                    'Jumping jacks: 30 sec',
                    'Burpees with kick: 30 sec',
                    'High knees: 30 sec',
                    'Shadow boxing with movement: 30 sec',
                    'Butt kicks: 30 sec',
                    '2 min cool down'
                ],
                'rounds' => 6,
                'intensity' => 'hard',
                'rest_minutes' => 1,
                'tags' => ['Cardio', 'Striking', 'Muay Thai', 'Circuit'],
            ],
            [
                'day_of_week' => 'friday',
                'title' => 'Full Muay Thai Circuit',
                'description' => 'Complete Muay Thai conditioning circuit',
                'exercises' => [
                    '3 min warm-up (full body movement)',
                    'Circuit (45 sec work/15 sec rest x 5 rounds):',
                    'All strikes combination: 45 sec',
                    'Clinch and knee work: 45 sec',
                    'Kick combinations: 45 sec',
                    'Core work (plank punches): 45 sec',
                    'Defensive movements: 45 sec',
                    'Full body burpee: 45 sec',
                    '2 min cool down stretching'
                ],
                'rounds' => 5,
                'intensity' => 'hard',
                'rest_minutes' => 1,
                'tags' => ['Full Body', 'Muay Thai', 'Circuit', 'Conditioning'],
            ],
            [
                'day_of_week' => 'saturday',
                'title' => 'Sparring Circuit & Application',
                'description' => 'Apply Muay Thai techniques in circuit format',
                'exercises' => [
                    '5 min warm-up and stretching',
                    'Circuit (1 min work/30 sec rest x 4 rounds):',
                    'Light sparring (if partner): 1 min',
                    'OR Technical combinations: 1 min',
                    'Clinch sparring: 1 min',
                    'Kick defense drills: 1 min',
                    'Counter-striking practice: 1 min',
                    'Pad work application: 1 min',
                    '3 min cool down'
                ],
                'rounds' => 4,
                'intensity' => 'medium',
                'rest_minutes' => 1,
                'tags' => ['Sparring', 'Application', 'Muay Thai', 'Circuit'],
            ],
            [
                'day_of_week' => 'sunday',
                'title' => 'Active Recovery Circuit',
                'description' => 'Light recovery with mobility circuits',
                'exercises' => [
                    '5 min gentle warm-up',
                    'Recovery Circuit (1 min work/2 min rest x 3 rounds):',
                    'Light shadow striking: 1 min',
                    'Mobility stretches: 1 min',
                    'Deep breathing: 1 min',
                    'Gentle kicks: 1 min',
                    'Meditation: 1 min',
                    'Light walking: 1 min'
                ],
                'rounds' => 3,
                'intensity' => 'easy',
                'rest_minutes' => 2,
                'tags' => ['Recovery', 'Mobility', 'Muay Thai', 'Light'],
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

    private function createBeginnerNoEquipmentRoutine(User $user): void
    {
        $routine = $user->workoutRoutines()->create([
            'name' => 'Beginner Bodyweight Fitness',
            'description' => 'A beginner-friendly weekly workout routine using only bodyweight exercises. Includes boxing-style conditioning and Muay Thai elements for improved fitness and coordination.',
            'is_active' => false,
        ]);

        $routine->days()->createMany([
            [
                'day_of_week' => 'monday',
                'title' => 'Full Body Strength & Boxing Basics',
                'description' => 'Build overall strength with basic boxing movements',
                'exercises' => [
                    '5 min warm-up (march in place, arm circles)',
                    'Push-ups: 3 sets of 8-10',
                    'Bodyweight squats: 3 sets of 12',
                    'Shadow boxing: 3 rounds of 2 min (jab, cross, hook)',
                    'Plank: 3 sets of 20-30 seconds',
                    '5 min cool down stretching'
                ],
                'rounds' => 4,
                'intensity' => 'easy',
                'rest_minutes' => 1,
                'tags' => ['Beginner', 'Bodyweight', 'Boxing', 'Strength'],
            ],
            [
                'day_of_week' => 'tuesday',
                'title' => 'Cardio & Footwork',
                'description' => 'Improve cardiovascular fitness and footwork',
                'exercises' => [
                    '5 min warm-up (light jogging in place)',
                    'High knees: 3 sets of 30 seconds',
                    'Butt kicks: 3 sets of 30 seconds',
                    'Boxing footwork drills: 3 rounds of 2 min',
                    'Mountain climbers: 3 sets of 20',
                    'Jumping jacks: 3 sets of 30',
                    '5 min cool down'
                ],
                'rounds' => 4,
                'intensity' => 'medium',
                'rest_minutes' => 1,
                'tags' => ['Cardio', 'Footwork', 'Boxing', 'Beginner'],
            ],
            [
                'day_of_week' => 'wednesday',
                'title' => 'Core & Muay Thai Conditioning',
                'description' => 'Strengthen core with Muay Thai-inspired movements',
                'exercises' => [
                    '5 min warm-up (torso twists, arm swings)',
                    'Crunches: 3 sets of 15',
                    'Russian twists: 3 sets of 20',
                    'Shadow Muay Thai kicks: 3 rounds of 2 min',
                    'Leg raises: 3 sets of 10',
                    'Superman holds: 3 sets of 20 seconds',
                    '5 min cool down stretching'
                ],
                'rounds' => 4,
                'intensity' => 'easy',
                'rest_minutes' => 1,
                'tags' => ['Core', 'Muay Thai', 'Conditioning', 'Beginner'],
            ],
            [
                'day_of_week' => 'thursday',
                'title' => 'Rest Day',
                'description' => 'Active recovery and light movement',
                'exercises' => [
                    '20 min light walking or gentle stretching',
                    'Deep breathing exercises: 5 min',
                    'Optional: Light shadow boxing practice (no intensity)'
                ],
                'rounds' => 1,
                'intensity' => 'easy',
                'rest_minutes' => 0,
                'tags' => ['Rest', 'Recovery', 'Active'],
            ],
            [
                'day_of_week' => 'friday',
                'title' => 'Upper Body & Combinations',
                'description' => 'Focus on upper body strength with boxing combinations',
                'exercises' => [
                    '5 min warm-up (arm circles, shoulder rolls)',
                    'Wall push-ups: 3 sets of 10-12',
                    'Pike push-ups: 3 sets of 6-8',
                    'Boxing combinations: 3 rounds of 2 min (jab-cross-hook)',
                    'Diamond push-ups: 3 sets of 8',
                    'Arm circles and stretches: 5 min'
                ],
                'rounds' => 4,
                'intensity' => 'easy',
                'rest_minutes' => 1,
                'tags' => ['Upper Body', 'Boxing', 'Combinations', 'Beginner'],
            ],
            [
                'day_of_week' => 'saturday',
                'title' => 'Full Circuit & Conditioning',
                'description' => 'Complete bodyweight circuit with martial arts elements',
                'exercises' => [
                    '5 min warm-up (full body movement)',
                    'Burpees: 3 sets of 5-8',
                    'Alternating lunges: 3 sets of 10 each leg',
                    'Shadow boxing round: 3 min continuous',
                    'Mountain climbers: 3 sets of 30 seconds',
                    'Plank to push-up: 3 sets of 6-8',
                    'Muay Thai roundhouse kick practice: 2 min',
                    '5 min cool down stretching'
                ],
                'rounds' => 5,
                'intensity' => 'medium',
                'rest_minutes' => 1,
                'tags' => ['Circuit', 'Conditioning', 'Full Body', 'Martial Arts'],
            ],
            [
                'day_of_week' => 'sunday',
                'title' => 'Rest Day',
                'description' => 'Complete rest and recovery',
                'exercises' => [
                    'Optional: 15-20 min gentle walk',
                    'Focus on recovery and relaxation'
                ],
                'rounds' => 1,
                'intensity' => 'easy',
                'rest_minutes' => 0,
                'tags' => ['Rest', 'Recovery', 'Relaxation'],
            ],
        ]);
    }
}
