<?php

namespace App\Services;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentService
{
    public function __construct() {}
    public function createComment(array $data)
    {
        $user = Auth::user();

        $status = 'pending';

        if (in_array($user->role, ['admin', 'editor'])) {
            $status = 'approved';
        }

        $comment = Comment::create([
            'post_id' => $data['post_id'],
            'user_id' => $user->id,
            'content' => $data['content'],
            'status' => $status,
            'is_admin_reply' => $user->role === 'admin'
        ]);

        return $comment;
    }
}
