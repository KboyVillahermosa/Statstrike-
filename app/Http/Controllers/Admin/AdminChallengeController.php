<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class AdminChallengeController extends Controller
{
    /**
     * Display a listing of all challenges.
     */
    public function index(Request $request): Response
    {
        $query = Challenge::withCount('users');

        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
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

        $challenges = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Challenges/Index', [
            'challenges' => $challenges,
            'filters' => [
                'search' => $request->get('search'),
                'filter_active' => $request->get('filter_active'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new challenge.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Challenges/Create');
    }

    /**
     * Store a newly created challenge.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'type' => ['required', 'string', 'in:kicks,core,endurance,strength'],
            'target_count' => ['required', 'integer', 'min:1'],
            'target_unit' => ['required', 'string', 'max:50'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'is_active' => ['boolean'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after:starts_at'],
            'image_url' => ['nullable', 'string', 'url', 'max:255'],
        ]);

        $challenge = Challenge::create($validated);

        return redirect()
            ->route('admin.challenges.show', $challenge)
            ->with('success', 'Challenge created successfully!');
    }

    /**
     * Display the specified challenge.
     */
    public function show(Challenge $challenge): Response
    {
        $challenge->load([
            'users' => function ($query) {
                $query->orderBy('pivot_progress', 'desc')->limit(20);
            },
            'sessions' => function ($query) {
                $query->latest()->limit(10);
            },
        ]);

        return Inertia::render('Admin/Challenges/Show', [
            'challenge' => $challenge,
        ]);
    }

    /**
     * Show the form for editing the specified challenge.
     */
    public function edit(Challenge $challenge): Response
    {
        return Inertia::render('Admin/Challenges/Edit', [
            'challenge' => $challenge,
        ]);
    }

    /**
     * Update the specified challenge.
     */
    public function update(Request $request, Challenge $challenge): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'type' => ['required', 'string', 'in:kicks,core,endurance,strength'],
            'target_count' => ['required', 'integer', 'min:1'],
            'target_unit' => ['required', 'string', 'max:50'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'is_active' => ['boolean'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after:starts_at'],
            'image_url' => ['nullable', 'string', 'url', 'max:255'],
        ]);

        $challenge->update($validated);

        return redirect()
            ->route('admin.challenges.show', $challenge)
            ->with('success', 'Challenge updated successfully!');
    }

    /**
     * Remove the specified challenge from storage.
     */
    public function destroy(Challenge $challenge): RedirectResponse
    {
        $challenge->delete();

        return redirect()
            ->route('admin.challenges.index')
            ->with('success', 'Challenge deleted successfully!');
    }

    /**
     * Toggle active status for a challenge.
     */
    public function toggleActive(Challenge $challenge): RedirectResponse
    {
        $challenge->update([
            'is_active' => !$challenge->is_active,
        ]);

        $status = $challenge->is_active ? 'activated' : 'deactivated';
        
        return redirect()
            ->route('admin.challenges.show', $challenge)
            ->with('success', "Challenge {$status} successfully!");
    }
}
