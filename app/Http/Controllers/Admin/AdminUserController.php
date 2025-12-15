<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class AdminUserController extends Controller
{
    /**
     * Display a listing of all users.
     */
    public function index(Request $request): Response
    {
        $query = User::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by admin status
        if ($request->has('filter_admin')) {
            $filterAdmin = $request->get('filter_admin');
            if ($filterAdmin === 'admin') {
                $query->where('is_admin', true);
            } elseif ($filterAdmin === 'regular') {
                $query->where('is_admin', false);
            }
        }

        $users = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'filters' => [
                'search' => $request->get('search'),
                'filter_admin' => $request->get('filter_admin'),
            ],
        ]);
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'is_admin' => ['boolean'],
            'subscription_tier' => ['nullable', 'string', 'in:free,standard,pro'],
            'fitness_goal' => ['nullable', 'string', 'max:255'],
            'experience_level' => ['nullable', 'string', 'in:beginner,intermediate,advanced,expert'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'is_admin' => $validated['is_admin'] ?? false,
            'subscription_tier' => $validated['subscription_tier'] ?? 'free',
            'fitness_goal' => $validated['fitness_goal'] ?? null,
            'experience_level' => $validated['experience_level'] ?? null,
        ]);

        // Redirect to dashboard if coming from dashboard, otherwise to users index
        $redirectTo = $request->header('Referer') && str_contains($request->header('Referer'), '/admin/dashboard')
            ? route('admin.dashboard')
            : route('admin.users.index');

        return redirect($redirectTo)
            ->with('success', 'User created successfully!');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user): Response
    {
        $user->load([
            'workouts' => function ($query) {
                $query->latest()->limit(10);
            },
            'workoutRoutines',
            'challenges' => function ($query) {
                $query->latest()->limit(10);
            },
            'posts' => function ($query) {
                $query->latest()->limit(10);
            },
        ]);

        return Inertia::render('Admin/Users/Show', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'fitness_goal' => ['nullable', 'string', 'max:255'],
            'experience_level' => ['nullable', 'string', 'in:beginner,intermediate,advanced,expert'],
            'subscription_tier' => ['nullable', 'string', 'in:free,standard,pro'],
            'points' => ['nullable', 'integer', 'min:0'],
        ]);

        $user->update($validated);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'You cannot delete your own account!');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }

    /**
     * Toggle admin status for a user.
     */
    public function toggleAdmin(User $user): RedirectResponse
    {
        // Prevent removing admin status from yourself
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('admin.users.show', $user)
                ->with('error', 'You cannot remove admin status from yourself!');
        }

        $user->update([
            'is_admin' => !$user->is_admin,
        ]);

        $status = $user->is_admin ? 'granted' : 'removed';
        
        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', "Admin status {$status} successfully!");
    }
}
