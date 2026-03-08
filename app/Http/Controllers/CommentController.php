<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\CommentService;
use App\Events\CommentCreated;
use App\Notifications\CommentAddedNotification;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private CommentService $service;
    public function __construct(CommentService $service)
    {
        $this->service = $service;
    }
    public function index($id)
    {
        // return Comment::where('post_id', $id)->latest()->get();
        return Comment::with(['user', 'post'])
            ->latest()
            ->paginate(20);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage. $user = Auth::user();
     */
    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'content' => 'required|string'
        ]);

        $user = Auth::user();

        $status = 'pending';

        if ($user->role === 'admin' || $user->role === 'editor') {
            $status = 'approved';
        }

        $comment = Comment::create([
            'post_id' => $request->input('post_id'),
            'user_id' => $user->id,
            'content' => $request->input('content'),
            'status' => $status,
            'is_admin_reply' => $user->role === 'admin'
        ]);
        $postOwner = $comment->post->user;

        if ($postOwner->id !== $comment->user_id) {
            $postOwner->notify(new CommentAddedNotification($comment));
        }

        $post = $comment->post;
        return response()->json($comment, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $user = auth()->user();

        // Admin override
        if ($user->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted'
        ]);
    }

    public function approve(Comment $comment)
    {
        $comment->update([
            'status' => 'approved'
        ]);

        return response()->json([
            'message' => 'Comment approved',
            'comment' => $comment
        ]);
    }
}
