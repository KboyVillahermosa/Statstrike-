<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\StorePostRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class CommunityController extends Controller
{
    /**
     * Display the community page with posts and leaderboard.
     */
    public function index(Request $request): Response
    {
        $user = $request->user();

        // Get posts with relationships, ordered by latest
        $posts = Post::with(['user', 'likes', 'comments.user'])
            ->latest()
            ->get()
            ->map(function ($post) use ($user) {
                return [
                    'id' => $post->id,
                    'content' => $post->content,
                    'image' => $post->image ? Storage::url($post->image) : null,
                    'created_at' => $post->created_at->diffForHumans(),
                    'user' => [
                        'id' => $post->user->id,
                        'name' => $post->user->name,
                        'profile_photo' => $post->user->profile_photo ? Storage::url($post->user->profile_photo) : null,
                        'initials' => $this->getInitials($post->user->name),
                    ],
                    'likes_count' => $post->likes_count,
                    'comments_count' => $post->comments_count,
                    'is_liked' => $user ? $post->isLikedBy($user->id) : false,
                    'comments' => $post->comments->map(function ($comment) {
                        return [
                            'id' => $comment->id,
                            'content' => $comment->content,
                            'created_at' => $comment->created_at->diffForHumans(),
                            'user' => [
                                'id' => $comment->user->id,
                                'name' => $comment->user->name,
                                'profile_photo' => $comment->user->profile_photo ? Storage::url($comment->user->profile_photo) : null,
                                'initials' => $this->getInitials($comment->user->name),
                            ],
                        ];
                    }),
                ];
            });

        // Get leaderboard (top 10 users by points)
        $leaderboard = User::orderBy('points', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($user, $index) {
                return [
                    'rank' => $index + 1,
                    'id' => $user->id,
                    'name' => $user->name,
                    'profile_photo' => $user->profile_photo ? Storage::url($user->profile_photo) : null,
                    'initials' => $this->getInitials($user->name),
                    'points' => $user->points ?? 0,
                    'experience_level' => $user->experience_level ?? 'beginner',
                ];
            });

        return Inertia::render('Community', [
            'posts' => $posts,
            'leaderboard' => $leaderboard,
        ]);
    }

    /**
     * Store a new post.
     */
    public function store(StorePostRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        $post = Post::create([
            'user_id' => $user->id,
            'content' => $data['content'],
            'image' => $data['image'] ?? null,
        ]);

        // Award points for creating a post
        $user->increment('points', 5);

        return redirect()
            ->route('community')
            ->with('success', 'Post created successfully!');
    }

    /**
     * Toggle like on a post.
     */
    public function toggleLike(Request $request, Post $post): RedirectResponse
    {
        $user = $request->user();

        $like = PostLike::where('post_id', $post->id)
            ->where('user_id', $user->id)
            ->first();

        if ($like) {
            // Unlike
            $like->delete();
            $message = 'Post unliked.';
        } else {
            // Like
            PostLike::create([
                'post_id' => $post->id,
                'user_id' => $user->id,
            ]);
            
            // Award points to post owner (if not the same user)
            if ($post->user_id !== $user->id) {
                $post->user->increment('points', 2);
            }
            
            $message = 'Post liked!';
        }

        return redirect()
            ->back()
            ->with('success', $message);
    }

    /**
     * Store a comment on a post.
     */
    public function storeComment(StoreCommentRequest $request, Post $post): RedirectResponse
    {
        $user = $request->user();

        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'content' => $request->validated()['content'],
        ]);

        // Award points to commenter
        $user->increment('points', 1);

        // Award points to post owner (if not the same user)
        if ($post->user_id !== $user->id) {
            $post->user->increment('points', 1);
        }

        return redirect()
            ->back()
            ->with('success', 'Comment added successfully!');
    }

    /**
     * Share a post (copy link to clipboard functionality is handled on frontend).
     */
    public function share(Request $request, Post $post): RedirectResponse
    {
        // Share functionality is primarily frontend-based (copy link)
        // This endpoint can be used for tracking shares if needed
        return redirect()->back();
    }

    /**
     * Get user initials for avatar display.
     */
    private function getInitials(string $name): string
    {
        $words = explode(' ', $name);
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($name, 0, 2));
    }
}
