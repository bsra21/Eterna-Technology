<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\CommentCreated;
use App\Notifications\CommentAddedNotification;

class SendCommentNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CommentCreated $event)
    {
        $comment = $event->comment;

        $postOwner = $comment->post->user;

        if ($postOwner && $postOwner->id !== $comment->user_id) {
            $postOwner->notify(
                new \App\Notifications\CommentAddedNotification($comment)
            );
        }
    }
}
