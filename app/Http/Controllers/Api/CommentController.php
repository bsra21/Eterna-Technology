<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;

use App\Notifications\CommentAddedNotification;
use App\Models\Post;

class CommentController extends Controller
{
    public function index(Post $post)
    {
        return Comment::with('user')
            ->where('post_id', $post->id)
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:5000'
        ]);

        $user = Auth::user();

        $role = $user?->role ?? 'user';
        $status = 'pending';

        if ($user && ($user->role === 'admin' || $user->role === 'editor')) {
            $status = 'approved';
        }

        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => $user?->id ?? 1,
            'content' => $validated['content'],
            'status' => $status,
            'role' => $role,
            'is_admin_reply' => $role === 'admin',
            'approved' => $status === 'approved'
        ]);

        $postOwner = $post->user;

        if ($postOwner && $postOwner->id !== $comment->user_id) {
            $postOwner->notify(new CommentAddedNotification($comment));
        }

        $post->calculateScore();
        //  event(new \App\Events\CommentCreated($comment));
        return response()->json([
            'message' => 'Comment created',
            'comment' => $comment
        ], 201);
    }

    public function approve($id)
    {
        $comment = Comment::findOrFail($id);

        $comment->update([
            'status' => 'approved'
        ]);

        return response()->json([
            'message' => 'Comment approved',
            'comment' => $comment
        ]);
    }
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully'
        ]);
    }

    public function allComments()
    {
        $comments = Comment::with('user', 'post')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($comments);
    }
}

// Controller/api/commentCotroller
