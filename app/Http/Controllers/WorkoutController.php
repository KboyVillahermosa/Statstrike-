<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkoutRequest;
use App\Models\Workout;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class WorkoutController extends Controller
{
    /**
     * Display the log workout form.
     */
    public function create(): Response
    {
        return Inertia::render('Workouts/LogWorkout');
    }

    /**
     * Store a newly created workout in storage.
     */
    public function store(StoreWorkoutRequest $request): RedirectResponse
    {
        $workout = $request->user()->workouts()->create($request->validated());

        return redirect()
            ->route('workouts.log')
            ->with('success', 'Workout logged successfully!');
    }
}
