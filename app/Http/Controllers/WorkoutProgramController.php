<?php

namespace App\Http\Controllers;

use App\Models\WorkoutProgram;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WorkoutProgramController extends Controller
{
    /**
     * Display workout programs.
     */
    public function index(Request $request): Response
    {
        $level = $request->query('level');
        $goal = $request->query('goal');

        $query = WorkoutProgram::with('exercises')->where('is_active', true);

        if ($level) {
            $query->where('level', $level);
        }

        if ($goal) {
            $query->where('goal', $goal);
        }

        $programs = $query->get();

        return Inertia::render('Workouts/WorkoutPrograms', [
            'programs' => $programs,
            'filters' => [
                'level' => $level,
                'goal' => $goal,
            ],
        ]);
    }

    /**
     * Show a single workout program.
     */
    public function show(WorkoutProgram $workoutProgram): Response
    {
        $workoutProgram->load(['exercises' => function ($query) {
            $query->orderByPivot('order');
        }]);

        return Inertia::render('Workouts/WorkoutProgramDetail', [
            'program' => $workoutProgram,
        ]);
    }
}
