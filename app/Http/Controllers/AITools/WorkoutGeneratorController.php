<?php

namespace App\Http\Controllers\AITools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WorkoutGeneratorController extends Controller
{
    /**
     * Generate AI-powered workout based on user input
     */
    public function generateWorkout(Request $request): JsonResponse
    {
        $request->validate([
            'fitnessGoals' => 'required|string|max:1000',
            'preferredStyle' => 'required|string|max:100',
            'workoutHistory' => 'required|string|max:1000',
            'otherConsiderations' => 'nullable|string|max:1000',
        ]);

        try {
            $userInput = [
                'fitnessGoals' => $request->fitnessGoals,
                'preferredStyle' => $request->preferredStyle,
                'workoutHistory' => $request->workoutHistory,
                'otherConsiderations' => $request->otherConsiderations,
            ];

            $generatedWorkout = $this->generateWorkoutWithAI($userInput);
            
            return response()->json([
                'success' => true,
                'workout' => $generatedWorkout,
            ]);

        } catch (\Exception $e) {
            Log::error('Workout generation failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate workout. Please try again.',
            ], 500);
        }
    }



    /**
     * Save generated workout as a routine
     */
    public function saveAsRoutine(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
            'days' => ['required', 'array', 'min:1'],
            'days.*.day_of_week' => ['required', 'string', 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'],
            'days.*.title' => ['required', 'string', 'max:255'],
            'days.*.description' => ['nullable', 'string', 'max:500'],
            'days.*.exercises' => ['required', 'array', 'min:1'],
            'days.*.exercises.*.label' => ['required', 'string'],
            'days.*.exercises.*.seconds' => ['required', 'integer', 'min:5', 'max:3600'],
            'days.*.rounds' => ['required', 'integer', 'min:1', 'max:20'],
            'days.*.intensity' => ['required', 'string', 'in:easy,medium,hard'],
            'days.*.rest_minutes' => ['required', 'integer', 'min:0', 'max:10'],
            'days.*.tags' => ['nullable', 'array'],
        ]);

        try {
            // Create workout routine using the authenticated user
            $routine = $request->user()->workoutRoutines()->create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'is_active' => true,
            ]);

            // Create workout routine days
            foreach ($validated['days'] as $dayData) {
                $routine->days()->create([
                    'day_of_week' => $dayData['day_of_week'],
                    'title' => $dayData['title'],
                    'description' => $dayData['description'] ?? null,
                    'exercises' => $dayData['exercises'],
                    'rounds' => $dayData['rounds'],
                    'intensity' => $dayData['intensity'],
                    'rest_minutes' => $dayData['rest_minutes'],
                    'tags' => $dayData['tags'] ?? [],
                ]);
            }

            // Load the routine with its days for the response
            $routine->load('days');

            return response()->json([
                'success' => true,
                'message' => 'Workout routine created successfully!',
                'routine' => $routine,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to save workout routine: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to save workout routine: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * AI-powered workout generation logic
     */
    private function generateWorkoutWithAI(array $userInput): array
    {
        $style = strtolower($userInput['preferredStyle']);
        $goals = strtolower($userInput['fitnessGoals']);
        $history = strtolower($userInput['workoutHistory']);
        $considerations = strtolower($userInput['otherConsiderations'] ?? '');

        // Determine workout frequency based on history
        $frequency = $this->analyzeFrequency($history);
        
        // Determine intensity level based on goals and history
        $intensity = $this->analyzeIntensity($goals, $history);
        
        // Get style-specific exercises
        $exerciseLibrary = $this->getExerciseLibrary($style);
        
        // Generate weekly schedule
        $weeklySchedule = $this->generateWeeklySchedule($frequency, $intensity, $exerciseLibrary);
        
        return [
            'name' => $this->generateRoutineName($style, $goals),
            'description' => $this->generateDescription($userInput),
            'is_active' => true,
            'days' => $weeklySchedule,
        ];
    }

    /**
     * Analyze user's workout frequency from history
     */
    private function analyzeFrequency(string $history): int
    {
        if (preg_match('/(\d+)\s*times?\s*per\s*week/i', $history, $matches)) {
            return min(7, max(2, (int) $matches[1]));
        }
        
        if (preg_match('/daily|every\s*day/i', $history)) {
            return 6; // 6 days with 1 rest day
        }
        
        if (preg_match('/(\d+)\s*times?\s*a\s*week/i', $history, $matches)) {
            return min(7, max(2, (int) $matches[1]));
        }

        // Default based on experience level
        if (preg_match('/beginner|new|starting/i', $history)) {
            return 3;
        } elseif (preg_match('/intermediate|experienced/i', $history)) {
            return 4;
        } else {
            return 5;
        }
    }

    /**
     * Analyze intensity level from goals and history
     */
    private function analyzeIntensity(string $goals, string $history): string
    {
        $intensityKeywords = [
            'hard' => ['intense', 'hard', 'aggressive', 'extreme', 'advanced'],
            'medium' => ['moderate', 'balanced', 'steady', 'consistent'],
            'easy' => ['light', 'easy', 'beginner', 'gentle', 'recovery'],
        ];

        $totalScore = 0;
        $text = $goals . ' ' . $history;

        foreach ($intensityKeywords as $level => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($text, $keyword)) {
                    if ($level === 'hard') $totalScore += 2;
                    elseif ($level === 'medium') $totalScore += 1;
                    else $totalScore -= 1;
                }
            }
        }

        if ($totalScore >= 2) return 'hard';
        if ($totalScore <= -2) return 'easy';
        return 'medium';
    }

    /**
     * Get exercise library based on martial arts style
     */
    private function getExerciseLibrary(string $style): array
    {
        $libraries = [
            'boxing' => [
                'heavy_bag' => ['Heavy Bag Combinations', 'Jab-Cross Combinations', 'Uppercut Drills', 'Hook Practice'],
                'shadow_boxing' => ['Shadow Boxing Round', 'Footwork Drills', 'Head Movement'],
                'speed_bag' => ['Speed Bag Round', 'Double-End Bag'],
                'conditioning' => ['Jump Rope', 'Burpees', 'Mountain Climbers', 'Sprints'],
                'strength' => ['Push-ups', 'Pull-ups', 'Core Work', 'Medicine Ball Throws'],
                'technique' => ['Pad Work', 'Focus Mitts', 'Defense Drills'],
            ],
            'muay thai' => [
                'heavy_bag' => ['Muay Thai Combinations', 'Elbow Drills', 'Knee Strikes', 'Leg Kicks'],
                'shadow_boxing' => ['Muay Thai Shadow Boxing', 'Clinch Training', 'Footwork'],
                'conditioning' => ['Jump Rope', 'Burpees', 'Mountain Climbers', 'Thai Pads'],
                'strength' => ['Push-ups', 'Pull-ups', 'Core Work', 'Leg Exercises'],
                'technique' => ['Pad Work', 'Clinch Drills', 'Defense Training'],
            ],
            'bjj' => [
                'gi' => ['Gi Drills', 'Positional Sparring', 'Submission Drills', 'Breakfalls'],
                'no_gi' => ['No-Gi Drills', 'Takedown Practice', 'Guard Work', 'Escapes'],
                'conditioning' => ['Cardio Rounds', 'Dynamic Warm-up', 'Flexibility'],
                'strength' => ['Pull-ups', 'Push-ups', 'Core Work', 'Leg Strength'],
                'technique' => ['Positional Training', 'Flow Rolling', 'Drilling'],
            ],
            'mma' => [
                'striking' => ['Mixed Striking', 'Footwork', 'Head Movement', 'Combo Work'],
                'grappling' => ['Takedown Drills', 'Ground Work', 'Submission Practice'],
                'conditioning' => ['Mixed Conditioning', 'Sparring', 'Cardio'],
                'strength' => ['Functional Strength', 'Explosive Power', 'Core Work'],
                'technique' => ['Mixed Training', 'Position Sparring', 'Technique Focus'],
            ],
        ];

        // Default to boxing if style not found
        if (!isset($libraries[$style])) {
            return $libraries['boxing'];
        }

        return $libraries[$style];
    }


    /**
     * Generate weekly workout schedule
     */
    private function generateWeeklySchedule(int $frequency, string $intensity, array $exerciseLibrary): array
    {
        $daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $schedule = [];
        
        // Calculate rounds and rest based on intensity
        $rounds = match($intensity) {
            'hard' => 8,
            'medium' => 6,
            'easy' => 4,
        };
        
        $restMinutes = match($intensity) {
            'hard' => 1,
            'medium' => 2,
            'easy' => 3,
        };
        
        $exerciseSeconds = match($intensity) {
            'hard' => 90,
            'medium' => 60,
            'easy' => 45,
        };

        // Determine workout days (spread them throughout the week)
        $workoutDays = array_slice($daysOfWeek, 0, $frequency);
        
        // Detect style from exercise library keys
        $style = $this->detectStyleFromLibrary($exerciseLibrary);
        
        $dayIndex = 0;
        foreach ($daysOfWeek as $day) {
            if (in_array($day, $workoutDays)) {
                $exercises = $this->generateDayExercises($exerciseLibrary, $intensity, $exerciseSeconds);
                $workoutTitle = $this->generateWorkoutTitle($exerciseLibrary, $intensity);
                
                $schedule[] = [
                    'day_of_week' => $day,
                    'title' => $workoutTitle,
                    'description' => $this->generateDayDescription($intensity, $style),
                    'exercises' => $exercises,
                    'rounds' => $rounds,
                    'intensity' => $intensity,
                    'rest_minutes' => $restMinutes,
                ];
            } else {
                // Rest day
                $schedule[] = [
                    'day_of_week' => $day,
                    'title' => 'Rest Day',
                    'description' => 'Active recovery and stretching',
                    'exercises' => [
                        ['label' => 'Light Stretching', 'seconds' => 900],
                        ['label' => 'Breathing Exercises', 'seconds' => 300],
                    ],
                    'rounds' => 1,
                    'intensity' => 'easy',
                    'rest_minutes' => 0,
                ];
            }
            $dayIndex++;
        }

        return $schedule;
    }

    /**
     * Detect martial arts style from exercise library
     */
    private function detectStyleFromLibrary(array $exerciseLibrary): string
    {
        $styleMap = [
            'heavy_bag' => 'boxing',
            'clinch' => 'muay thai',
            'gi' => 'bjj',
            'takedown' => 'mma',
        ];

        foreach ($styleMap as $key => $style) {
            if (array_key_exists($key, $exerciseLibrary)) {
                return $style;
            }
        }

        return 'training';
    }

    /**
     * Generate exercises for a specific day
     */
    private function generateDayExercises(array $exerciseLibrary, string $intensity, int $exerciseSeconds): array
    {
        $exercises = [];
        $categories = array_keys($exerciseLibrary);
        
        // Select 4-6 exercises based on intensity
        $exerciseCount = match($intensity) {
            'hard' => 6,
            'medium' => 5,
            'easy' => 4,
        };
        
        $selectedCategories = array_rand($categories, min($exerciseCount, count($categories)));
        if (!is_array($selectedCategories)) {
            $selectedCategories = [$selectedCategories];
        }
        
        foreach ($selectedCategories as $category) {
            $categoryExercises = $exerciseLibrary[$categories[$category]];
            $selectedExercise = $categoryExercises[array_rand($categoryExercises)];
            $exercises[] = [
                'label' => $selectedExercise,
                'seconds' => $exerciseSeconds,
            ];
        }
        
        return $exercises;
    }

    /**
     * Generate workout title based on exercises and intensity
     */
    private function generateWorkoutTitle(array $exerciseLibrary, string $intensity): string
    {
        $titles = [
            'hard' => ['Intense Training Session', 'High-Intensity Workout', 'Advanced Training'],
            'medium' => ['Regular Training Session', 'Balanced Workout', 'Standard Practice'],
            'easy' => ['Light Training Session', 'Recovery Workout', 'Gentle Practice'],
        ];
        
        $titleOptions = $titles[$intensity];
        return $titleOptions[array_rand($titleOptions)];
    }

    /**
     * Generate day description
     */
    private function generateDayDescription(string $intensity, string $style): string
    {
        $descriptions = [
            'hard' => "Intense {$style} training focusing on technique and conditioning.",
            'medium' => "Balanced {$style} session for skill development and fitness.",
            'easy' => "Light {$style} training for technique refinement and recovery.",
        ];
        
        return $descriptions[$intensity];
    }

    /**
     * Generate routine name
     */
    private function generateRoutineName(string $style, string $goals): string
    {
        $styleName = ucfirst($style);
        $goalWords = explode(' ', $goals);
        $mainGoal = $goalWords[0] ?? 'Training';
        
        return "{$styleName} {$mainGoal} Program";
    }

    /**
     * Generate routine description
     */
    private function generateDescription(array $userInput): string
    {
        $style = $userInput['preferredStyle'];
        $goals = $userInput['fitnessGoals'];
        
        return "AI-generated {$style} training program focused on {$goals}. This program adapts to your current fitness level and available time.";
    }
}
