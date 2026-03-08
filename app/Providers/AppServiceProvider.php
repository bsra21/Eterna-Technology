<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Post;
use App\Policies\PostPolicy;
use Illuminate\Support\Facades\Gate;
use App\Models\Category;
use App\Policies\CategoryPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(Post::class, PostPolicy::class);
    }
}
