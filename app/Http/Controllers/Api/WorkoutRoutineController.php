<?php

namespace App\Http\Controllers\Api;

use App\Models\WorkoutRoutine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkoutRoutineController extends BaseController
{
    /**
     * List user's workout routines.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $routines = $user->workoutRoutines()
            ->with('days')
            ->orderBy('is_active', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->success($routines);
    }

    /**
     * Get a single workout routine.
     */
    public function show(Request $request, WorkoutRoutine $workoutRoutine): JsonResponse
    {
        // Check ownership
        if ($request->user()->id !== $workoutRoutine->user_id) {
            return $this->error('Unauthorized', null, 403);
        }

        $workoutRoutine->load('days');

        return $this->success($workoutRoutine);
    }

    /**
     * Create a new workout routine.
     */
    public function store(Request $request): JsonResponse
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
            'is_active' => $validated['is_active'] ?? true,
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

        $routine->load('days');

        return $this->success($routine, 'Workout routine created successfully', 201);
    }

    /**
     * Update a workout routine.
     */
    public function update(Request $request, WorkoutRoutine $workoutRoutine): JsonResponse
    {
        // Check ownership
        if ($request->user()->id !== $workoutRoutine->user_id) {
            return $this->error('Unauthorized', null, 403);
        }

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

        $workoutRoutine->load('days');

        return $this->success($workoutRoutine, 'Workout routine updated successfully');
    }

    /**
     * Delete a workout routine.
     */
    public function destroy(Request $request, WorkoutRoutine $workoutRoutine): JsonResponse
    {
        // Check ownership
        if ($request->user()->id !== $workoutRoutine->user_id) {
            return $this->error('Unauthorized', null, 403);
        }

        $workoutRoutine->delete();

        return $this->success(null, 'Workout routine deleted successfully');
    }
}
