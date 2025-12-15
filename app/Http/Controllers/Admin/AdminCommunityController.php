<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class AdminCommunityController extends Controller
{
    /**
     * Display a listing of all community posts.
     */
    public function index(Request $request): Response
    {
        $query = Post::with(['user', 'likes', 'comments']);

        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('content', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                 ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $posts = $query->withCount(['likes', 'comments'])
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Admin/Community/Index', [
            'posts' => $posts,
            'filters' => [
                'search' => $request->get('search'),
            ],
        ]);
    }

    /**
     * Display the specified post.
     */
    public function show(Post $post): Response
    {
        $post->load([
            'user',
            'likes' => function ($query) {
                $query->with('user')->latest();
            },
            'comments' => function ($query) {
                $query->with('user')->latest()->limit(50);
            },
        ]);

        return Inertia::render('Admin/Community/Show', [
            'post' => [
                'id' => $post->id,
                'content' => $post->content,
                'image' => $post->image ? Storage::url($post->image) : null,
                'created_at' => $post->created_at,
                'user' => [
                    'id' => $post->user->id,
                    'name' => $post->user->name,
                    'email' => $post->user->email,
                    'profile_photo' => $post->user->profile_photo ? Storage::url($post->user->profile_photo) : null,
                ],
                'likes_count' => $post->likes()->count(),
                'comments_count' => $post->comments()->count(),
                'likes' => $post->likes->map(function ($like) {
                    return [
                        'id' => $like->id,
                        'user' => [
                            'id' => $like->user->id,
                            'name' => $like->user->name,
                            'email' => $like->user->email,
                        ],
                        'created_at' => $like->created_at,
                    ];
                }),
                'comments' => $post->comments->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'content' => $comment->content,
                        'created_at' => $comment->created_at,
                        'user' => [
                            'id' => $comment->user->id,
                            'name' => $comment->user->name,
                            'email' => $comment->user->email,
                            'profile_photo' => $comment->user->profile_photo ? Storage::url($comment->user->profile_photo) : null,
                        ],
                    ];
                }),
            ],
        ]);
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy(Post $post): RedirectResponse
    {
        // Delete associated image if exists
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return redirect()
            ->route('admin.community.posts.index')
            ->with('success', 'Post deleted successfully!');
    }
}
