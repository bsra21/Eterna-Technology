<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))

    /*
    |--------------------------------------------------------------------------
    | Routing
    |--------------------------------------------------------------------------
    */

    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    /*
    |--------------------------------------------------------------------------
    | Middleware Aliases
    |--------------------------------------------------------------------------
    */

    ->withMiddleware(function (Middleware $middleware): void {

        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })

    /*
    |--------------------------------------------------------------------------
    | Scheduler (CRON JOBS)
    |--------------------------------------------------------------------------
    */

    ->withSchedule(function (Schedule $schedule): void {

        // 7 gün yorum almayan published postları sil
        $schedule->call(function () {

            \App\Models\Post::where('status', 'published')
                ->whereNull('deleted_at')
                ->whereDoesntHave('comments', function ($query) {
                    $query->where('created_at', '>=', now()->subDays(7))
                        ->where('approved', true);
                })
                ->each(function ($post) {
                    $post->delete();
                });
        })->daily();
    })

    /*
    |--------------------------------------------------------------------------
    | Exceptions
    |--------------------------------------------------------------------------
    */

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })

    ->create();
