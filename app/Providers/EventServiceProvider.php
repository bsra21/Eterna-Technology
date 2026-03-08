<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\CommentCreated;
use App\Listeners\SendCommentNotification;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        CommentCreated::class => [
            SendCommentNotification::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
