<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\ExerciseCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ExerciseController extends Controller
{
    /**
     * Display the exercise library.
     */
    public function index(Request $request): Response
    {
        $categorySlug = $request->query('category');
        $difficulty = $request->query('difficulty');

        $query = Exercise::with('category')->where('is_active', true);

        if ($categorySlug) {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        if ($difficulty) {
            $query->where('difficulty', $difficulty);
        }

        $exercises = $query->get();
        $categories = ExerciseCategory::all();

        return Inertia::render('Workouts/ExerciseLibrary', [
            'exercises' => $exercises,
            'categories' => $categories,
            'filters' => [
                'category' => $categorySlug,
                'difficulty' => $difficulty,
            ],
        ]);
    }

    /**
     * Show a single exercise.
     */
    public function show(Exercise $exercise): Response
    {
        $exercise->load('category');

        return Inertia::render('Workouts/ExerciseDetail', [
            'exercise' => $exercise,
        ]);
    }
}
