<?php

namespace App\Http\Controllers;

use App\Models\WorkoutRoutine;
use App\Models\WorkoutRoutineDay;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class WorkoutRoutineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $user = $request->user();
        
        $routines = $user->workoutRoutines()
            ->with('days')
            ->orderBy('is_active', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Workouts/WorkoutTemplates', [
            'routines' => $routines,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
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
            'days.*.exercises.*' => ['required', 'string'],
            'days.*.rounds' => ['required', 'integer', 'min:1', 'max:20'],
            'days.*.intensity' => ['required', 'string', 'in:easy,medium,hard'],
            'days.*.rest_minutes' => ['required', 'integer', 'min:0', 'max:10'],
            'days.*.tags' => ['nullable', 'array'],
        ]);

        $user = $request->user();

        $routine = $user->workoutRoutines()->create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => true,
        ]);

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

        return redirect()
            ->route('workouts.templates')
            ->with('success', 'Workout routine created successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WorkoutRoutine $workoutRoutine): RedirectResponse
    {
        $this->authorize('update', $workoutRoutine);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
            'days' => ['required', 'array', 'min:1'],
            'days.*.day_of_week' => ['required', 'string', 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'],
            'days.*.title' => ['required', 'string', 'max:255'],
            'days.*.description' => ['nullable', 'string', 'max:500'],
            'days.*.exercises' => ['required', 'array', 'min:1'],
            'days.*.exercises.*' => ['required', 'string'],
            'days.*.rounds' => ['required', 'integer', 'min:1', 'max:20'],
            'days.*.intensity' => ['required', 'string', 'in:easy,medium,hard'],
            'days.*.rest_minutes' => ['required', 'integer', 'min:0', 'max:10'],
            'days.*.tags' => ['nullable', 'array'],
        ]);

        $workoutRoutine->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        // Delete existing days and recreate
        $workoutRoutine->days()->delete();

        foreach ($validated['days'] as $dayData) {
            $workoutRoutine->days()->create([
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

        return redirect()
            ->route('workouts.templates')
            ->with('success', 'Workout routine updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkoutRoutine $workoutRoutine): RedirectResponse
    {
        $this->authorize('delete', $workoutRoutine);

        $workoutRoutine->delete();

        return redirect()
            ->route('workouts.templates')
            ->with('success', 'Workout routine deleted successfully!');
    }
}
