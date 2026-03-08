<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| PUBLIC AUTH ROUTES
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES (LOGIN OLAN HERKES)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | BLOG PUBLIC ACTIONS
    |--------------------------------------------------------------------------
    */
    Route::get('/posts/featured', [PostController::class, 'featured']);
    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/posts/{post}', [PostController::class, 'show']);

    Route::post('/posts/{post}/click', [PostController::class, 'click']);

    /*
    |--------------------------------------------------------------------------
    | COMMENTS
    |--------------------------------------------------------------------------
    */

    Route::get('/posts/{post}/comments', [CommentController::class, 'index']);
    Route::post('/posts/{post}/comments', [CommentController::class, 'store']);

    Route::post('/logout', [AuthController::class, 'logout']);
});

/*
|--------------------------------------------------------------------------
| EDITOR + ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum', 'role:editor,admin'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | POSTS MANAGEMENT
    |--------------------------------------------------------------------------
    */

    Route::post('/posts', [PostController::class, 'store']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy']);
    Route::put('/posts/{post}', [PostController::class, 'update']);
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

    /*
    |--------------------------------------------------------------------------
    | CATEGORY READ (EDITOR + ADMIN)
    |--------------------------------------------------------------------------
    */
    Route::get('/categories', [CategoryController::class, 'index']);
});

/*
|--------------------------------------------------------------------------
| ADMIN ONLY ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | USER MANAGEMENT
    |--------------------------------------------------------------------------
    */

    Route::get('/users', [UserController::class, 'index']);
    Route::put('/users/{user}/role', [UserController::class, 'updateRole']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | CATEGORY CRUD
    |--------------------------------------------------------------------------
    */

    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | COMMENT MODERATION
    |--------------------------------------------------------------------------
    */
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
    Route::get('/admin/comments', [CommentController::class, 'allComments']);
    Route::patch('/comments/{comment}/approve', [CommentController::class, 'approve']);

    /*
    |--------------------------------------------------------------------------
    | FEATURED POSTS
    |--------------------------------------------------------------------------
    */
});
