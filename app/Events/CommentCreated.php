<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CommentCreated implements ShouldBroadcast
{
    use Dispatchable, SerializesModels, InteractsWithSockets;

    public Comment $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function broadcastOn()
    {
        return ['comments'];
    }

    public function broadcastAs()
    {
        return 'comment.created';
    }
}
