<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function stats()
    {
        return Cache::remember('dashboard_stats', 60, function () {
            return response()->json([
                'total_posts' => Post::count(),
                'total_comments' => Comment::count(),
                'total_users' => User::count(),

                'latest_posts' => Post::latest()
                    ->take(5)
                    ->get(),

                'latest_comments' => Comment::latest()
                    ->take(5)
                    ->with(['user', 'post'])
                    ->get()
            ]);
        });
    }
}
