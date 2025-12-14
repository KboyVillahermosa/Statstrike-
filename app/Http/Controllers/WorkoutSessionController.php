<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\WorkoutProgram;
use App\Models\WorkoutSession;
use App\Models\WorkoutSessionExercise;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WorkoutSessionController extends Controller
{
    /**
     * Display the manual workout tracker.
     */
    public function create(Request $request): Response
    {
        $programId = $request->query('program');
        $program = null;
        $exercises = [];

        if ($programId) {
            $program = WorkoutProgram::with(['exercises' => function ($query) {
                $query->orderByPivot('order');
            }])->find($programId);
            
            if ($program) {
                $exercises = $program->exercises;
            }
        }

        $allExercises = Exercise::where('is_active', true)->get();
        $programs = WorkoutProgram::where('is_active', true)->get();

        return Inertia::render('Workouts/ManualWorkoutTracker', [
            'program' => $program,
            'exercises' => $exercises,
            'allExercises' => $allExercises,
            'programs' => $programs,
        ]);
    }

    /**
     * Start a new workout session.
     */
    public function start(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'workout_program_id' => ['nullable', 'exists:workout_programs,id'],
            'name' => ['nullable', 'string', 'max:255'],
        ]);

        $session = WorkoutSession::create([
            'user_id' => $request->user()->id,
            'workout_program_id' => $validated['workout_program_id'] ?? null,
            'name' => $validated['name'] ?? null,
            'started_at' => now(),
        ]);

        return redirect()->route('workout-sessions.track', $session);
    }

    /**
     * Display the active workout tracking page.
     */
    public function track(WorkoutSession $workoutSession): Response
    {
        $workoutSession->load(['exercises.exercise', 'workoutProgram']);
        $allExercises = Exercise::where('is_active', true)->with('category')->get();

        return Inertia::render('Workouts/ActiveWorkoutTracker', [
            'session' => $workoutSession,
            'allExercises' => $allExercises,
        ]);
    }

    /**
     * Add an exercise to the current session.
     */
    public function addExercise(Request $request, WorkoutSession $workoutSession): RedirectResponse
    {
        $validated = $request->validate([
            'exercise_id' => ['required', 'exists:exercises,id'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        $order = $validated['order'] ?? $workoutSession->exercises()->max('order') + 1;

        WorkoutSessionExercise::create([
            'workout_session_id' => $workoutSession->id,
            'exercise_id' => $validated['exercise_id'],
            'order' => $order,
        ]);

        return redirect()->back();
    }

    /**
     * Update exercise tracking data.
     */
    public function updateExercise(Request $request, WorkoutSession $workoutSession, WorkoutSessionExercise $exercise): RedirectResponse
    {
        $validated = $request->validate([
            'sets_completed' => ['nullable', 'integer', 'min:0'],
            'reps_completed' => ['nullable', 'integer', 'min:0'],
            'duration_seconds' => ['nullable', 'integer', 'min:0'],
            'set_data' => ['nullable', 'array'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $exercise->update($validated);

        // Update session totals
        $this->updateSessionTotals($workoutSession);

        return redirect()->back();
    }

    /**
     * Complete the workout session.
     */
    public function complete(Request $request, WorkoutSession $workoutSession): RedirectResponse
    {
        $validated = $request->validate([
            'rpe' => ['nullable', 'integer', 'between:1,10'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $duration = now()->diffInSeconds($workoutSession->started_at);

        $workoutSession->update([
            'completed_at' => now(),
            'duration_seconds' => $duration,
            'rpe' => $validated['rpe'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        $this->updateSessionTotals($workoutSession);

        return redirect()->route('workout-sessions.summary', $workoutSession)
            ->with('success', 'Workout completed successfully!');
    }

    /**
     * Display workout session summary.
     */
    public function summary(WorkoutSession $workoutSession): Response
    {
        $workoutSession->load(['exercises.exercise', 'workoutProgram']);

        return Inertia::render('Workouts/WorkoutSummary', [
            'session' => $workoutSession,
        ]);
    }

    /**
     * Update session totals based on exercises.
     */
    private function updateSessionTotals(WorkoutSession $session): void
    {
        $exercises = $session->exercises;
        
        $session->update([
            'total_exercises' => $exercises->count(),
            'total_sets' => $exercises->sum('sets_completed'),
            'total_reps' => $exercises->sum('reps_completed'),
        ]);
    }
}
