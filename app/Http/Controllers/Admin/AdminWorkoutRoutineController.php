<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkoutRoutine;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class AdminWorkoutRoutineController extends Controller
{
    /**
     * Display a listing of all workout routines.
     */
    public function index(Request $request): Response
    {
        $query = WorkoutRoutine::with(['user', 'days']);

        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                 ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by active status
        if ($request->has('filter_active')) {
            $filterActive = $request->get('filter_active');
            if ($filterActive === 'active') {
                $query->where('is_active', true);
            } elseif ($filterActive === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $routines = $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Admin/WorkoutRoutines/Index', [
            'routines' => $routines,
            'filters' => [
                'search' => $request->get('search'),
                'filter_active' => $request->get('filter_active'),
            ],
        ]);
    }

    /**
     * Display the specified workout routine.
     */
    public function show(WorkoutRoutine $workoutRoutine): Response
    {
        $workoutRoutine->load(['user', 'days']);

        return Inertia::render('Admin/WorkoutRoutines/Show', [
            'routine' => [
                'id' => $workoutRoutine->id,
                'name' => $workoutRoutine->name,
                'description' => $workoutRoutine->description,
                'is_active' => $workoutRoutine->is_active,
                'created_at' => $workoutRoutine->created_at,
                'updated_at' => $workoutRoutine->updated_at,
                'user' => [
                    'id' => $workoutRoutine->user->id,
                    'name' => $workoutRoutine->user->name,
                    'email' => $workoutRoutine->user->email,
                ],
                'days' => $workoutRoutine->days->map(function ($day) {
                    return [
                        'id' => $day->id,
                        'day_of_week' => $day->day_of_week,
                        'title' => $day->title,
                        'description' => $day->description,
                        'exercises' => $day->exercises,
                        'rounds' => $day->rounds,
                        'intensity' => $day->intensity,
                        'rest_minutes' => $day->rest_minutes,
                        'tags' => $day->tags,
                    ];
                }),
            ],
        ]);
    }

    /**
     * Remove the specified workout routine from storage.
     */
    public function destroy(WorkoutRoutine $workoutRoutine): RedirectResponse
    {
        $workoutRoutine->delete();

        return redirect()
            ->route('admin.workout-routines.index')
            ->with('success', 'Workout routine deleted successfully!');
    }
}
