<?php

namespace Database\Seeders;

use App\Models\Exercise;
use App\Models\ExerciseCategory;
use Illuminate\Database\Seeder;

class ExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $strength = ExerciseCategory::where('slug', 'strength')->first();
        $cardio = ExerciseCategory::where('slug', 'cardio')->first();
        $hiit = ExerciseCategory::where('slug', 'hiit')->first();
        $mobility = ExerciseCategory::where('slug', 'mobility')->first();
        $core = ExerciseCategory::where('slug', 'core')->first();

        $exercises = [
            // Strength Exercises
            [
                'category_id' => $strength->id,
                'name' => 'Push-ups',
                'slug' => 'push-ups',
                'description' => 'Classic upper body exercise targeting chest, shoulders, and triceps.',
                'instructions' => "1. Start in plank position with hands shoulder-width apart\n2. Lower your body until chest nearly touches floor\n3. Push back up to starting position\n4. Keep core engaged throughout",
                'target_muscles' => ['Chest', 'Shoulders', 'Triceps'],
                'difficulty' => 'beginner',
                'demo_video_url' => '/videos/placeholder.mp4',
            ],
            [
                'category_id' => $strength->id,
                'name' => 'Squats',
                'slug' => 'squats',
                'description' => 'Fundamental lower body exercise for legs and glutes.',
                'instructions' => "1. Stand with feet shoulder-width apart\n2. Lower down as if sitting in a chair\n3. Keep knees behind toes\n4. Return to standing position",
                'target_muscles' => ['Quadriceps', 'Glutes', 'Hamstrings'],
                'difficulty' => 'beginner',
                'demo_video_url' => '/videos/placeholder.mp4',
            ],
            [
                'category_id' => $strength->id,
                'name' => 'Pull-ups',
                'slug' => 'pull-ups',
                'description' => 'Advanced upper body exercise for back and biceps.',
                'instructions' => "1. Hang from bar with palms facing away\n2. Pull body up until chin clears bar\n3. Lower with control\n4. Repeat",
                'target_muscles' => ['Back', 'Biceps', 'Shoulders'],
                'difficulty' => 'advanced',
                'demo_video_url' => '/videos/placeholder.mp4',
            ],
            [
                'category_id' => $strength->id,
                'name' => 'Deadlifts',
                'slug' => 'deadlifts',
                'description' => 'Compound exercise targeting entire posterior chain.',
                'instructions' => "1. Stand with feet hip-width apart\n2. Hinge at hips, keeping back straight\n3. Lower weight while maintaining form\n4. Drive through heels to return up",
                'target_muscles' => ['Hamstrings', 'Glutes', 'Back'],
                'difficulty' => 'intermediate',
                'demo_video_url' => '/videos/placeholder.mp4',
            ],

            // Cardio Exercises
            [
                'category_id' => $cardio->id,
                'name' => 'Jumping Jacks',
                'slug' => 'jumping-jacks',
                'description' => 'Full-body cardio exercise to get your heart rate up.',
                'instructions' => "1. Stand with feet together, arms at sides\n2. Jump while spreading legs and raising arms\n3. Jump back to starting position\n4. Repeat rhythmically",
                'target_muscles' => ['Full Body'],
                'difficulty' => 'beginner',
                'duration_seconds' => 60,
                'demo_video_url' => '/videos/placeholder.mp4',
            ],
            [
                'category_id' => $cardio->id,
                'name' => 'Burpees',
                'slug' => 'burpees',
                'description' => 'High-intensity full-body exercise combining squat, plank, and jump.',
                'instructions' => "1. Start standing\n2. Drop into squat, place hands on floor\n3. Jump feet back to plank position\n4. Do a push-up (optional)\n5. Jump feet forward, then jump up",
                'target_muscles' => ['Full Body'],
                'difficulty' => 'intermediate',
                'demo_video_url' => '/videos/placeholder.mp4',
            ],
            [
                'category_id' => $cardio->id,
                'name' => 'Mountain Climbers',
                'slug' => 'mountain-climbers',
                'description' => 'Dynamic core and cardio exercise.',
                'instructions' => "1. Start in plank position\n2. Alternate bringing knees to chest\n3. Keep core engaged\n4. Move at a steady pace",
                'target_muscles' => ['Core', 'Shoulders', 'Legs'],
                'difficulty' => 'intermediate',
                'duration_seconds' => 30,
                'demo_video_url' => '/videos/placeholder.mp4',
            ],

            // HIIT Exercises
            [
                'category_id' => $hiit->id,
                'name' => 'High Knees',
                'slug' => 'high-knees',
                'description' => 'Explosive cardio exercise for HIIT workouts.',
                'instructions' => "1. Stand tall with feet hip-width apart\n2. Run in place, bringing knees up high\n3. Pump arms naturally\n4. Maintain quick pace",
                'target_muscles' => ['Legs', 'Core', 'Cardiovascular'],
                'difficulty' => 'beginner',
                'duration_seconds' => 30,
                'demo_video_url' => '/videos/placeholder.mp4',
            ],
            [
                'category_id' => $hiit->id,
                'name' => 'Jump Squats',
                'slug' => 'jump-squats',
                'description' => 'Explosive plyometric exercise combining strength and cardio.',
                'instructions' => "1. Start in squat position\n2. Explosively jump up\n3. Land softly back in squat\n4. Repeat immediately",
                'target_muscles' => ['Quadriceps', 'Glutes', 'Calves'],
                'difficulty' => 'intermediate',
                'demo_video_url' => '/videos/placeholder.mp4',
            ],

            // Mobility Exercises
            [
                'category_id' => $mobility->id,
                'name' => 'Cat-Cow Stretch',
                'slug' => 'cat-cow-stretch',
                'description' => 'Gentle spinal mobility exercise.',
                'instructions' => "1. Start on hands and knees\n2. Arch back (cow) - look up\n3. Round spine (cat) - tuck chin\n4. Alternate slowly",
                'target_muscles' => ['Spine', 'Core'],
                'difficulty' => 'beginner',
                'duration_seconds' => 60,
                'demo_video_url' => '/videos/placeholder.mp4',
            ],
            [
                'category_id' => $mobility->id,
                'name' => 'Hip Circles',
                'slug' => 'hip-circles',
                'description' => 'Improve hip mobility and range of motion.',
                'instructions' => "1. Stand with hands on hips\n2. Circle hips clockwise\n3. Reverse direction\n4. Keep core engaged",
                'target_muscles' => ['Hips', 'Core'],
                'difficulty' => 'beginner',
                'duration_seconds' => 30,
                'demo_video_url' => '/videos/placeholder.mp4',
            ],

            // Core Exercises
            [
                'category_id' => $core->id,
                'name' => 'Plank',
                'slug' => 'plank',
                'description' => 'Isometric core strengthening exercise.',
                'instructions' => "1. Start in push-up position\n2. Hold body straight from head to heels\n3. Engage core\n4. Breathe normally",
                'target_muscles' => ['Core', 'Shoulders'],
                'difficulty' => 'beginner',
                'duration_seconds' => 60,
                'demo_video_url' => '/videos/placeholder.mp4',
            ],
            [
                'category_id' => $core->id,
                'name' => 'Crunches',
                'slug' => 'crunches',
                'description' => 'Classic abdominal exercise.',
                'instructions' => "1. Lie on back with knees bent\n2. Place hands behind head\n3. Lift shoulders off ground\n4. Lower with control",
                'target_muscles' => ['Abdominals'],
                'difficulty' => 'beginner',
                'demo_video_url' => '/videos/placeholder.mp4',
            ],
            [
                'category_id' => $core->id,
                'name' => 'Russian Twists',
                'slug' => 'russian-twists',
                'description' => 'Rotational core exercise.',
                'instructions' => "1. Sit with knees bent, lean back slightly\n2. Rotate torso side to side\n3. Keep core engaged\n4. Add weight for difficulty",
                'target_muscles' => ['Obliques', 'Core'],
                'difficulty' => 'intermediate',
                'demo_video_url' => '/videos/placeholder.mp4',
            ],
        ];

        foreach ($exercises as $exercise) {
            Exercise::create($exercise);
        }
    }
}
