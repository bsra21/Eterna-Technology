<?php

namespace App\Http\Controllers;


use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use AuthorizesRequests;
    public function index()
    {
        $user = auth()->user();

        $query = Post::query()->with(['categories', 'user']);

        // Editor ise sadece kendi postlarını gör
        if ($user->role === 'editor') {
            $query->where('user_id', $user->id);
        }

        // Soft delete hariç
        $query->whereNull('deleted_at');

        return response()->json(
            $query->latest()->get()
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        // Temel slug
        $slug = Str::slug($request->input('title'));

        // Benzersiz hale getir
        $originalSlug = $slug;
        $counter = 1;
        while (Post::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        // $userId = Auth::id();
        $status = $request->validated()['status'] ?? 'draft';
        $post = Post::create([
            'user_id' => auth()->user()->id, //  'user_id' => $userId, // Test kullanıcı
            'title' => $request->input('title'),
            'slug' => $slug,
            'content' => $request->input('content'),
            'status' => $status,
            'published_at' => $request->input('published_at'),
        ]);

        $post->categories()->sync($request->input('categories', []));

        if ($request->hasFile('cover')) {
            $post->addMediaFromRequest('cover')->toMediaCollection('cover');
        }

        return response()->json($post->load('categories'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->increment('views');
        $post->calculateScore();
        // return Post::findOrFail($post);
        return response()->json($post);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $this->authorize('update', $post);
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string'
        ]);

        $post->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->input('content'),
        ]);
        // $post->update($request->validated());

        if ($request->has('categories')) {
            $post->categories()->sync($request->categories);
        }

        if ($request->status === 'published' && !$post->published_at) {
            $post->published_at = now();
        }

        if ($request->hasFile('cover')) {
            $post->clearMediaCollection('cover');
            $post->addMediaFromRequest('cover')->toMediaCollection('cover');
        }

        return response()->json(
            $post->load('categories')
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Post $post)
    {
        if ($request->user()->role !== 'admin') {
            abort(403);
        }

        $this->authorize('delete', $post);

        $post->delete();

        return response()->json([
            'message' => 'Post deleted'
        ]);
    }

    public function featured()
    {
        // Yayında olan postları al, eager load comments
        $posts = Post::with(['comments' => function ($q) {
            $q->where('approved', true);
        }])
            ->where('status', 'published')
            ->whereNull('deleted_at')
            ->get();

        $result = $posts->map(function ($post) {
            $comments = $post->comments;

            // Son 24 saat
            $comments24h = $comments->filter(function ($c) {
                return $c->created_at >= now()->subDay();
            })->count();

            // Son 7 gün
            $comments7d = $comments->filter(function ($c) {
                return $c->created_at >= now()->subDays(7);
            })->count();

            $totalComments = $comments->count();

            // Yayın tarihi bonusu
            $ageBonus = 0;
            if ($post->published_at && $post->published_at >= now()->subDay()) {
                $ageBonus = 20;
            } elseif ($post->published_at && $post->published_at >= now()->subDays(3)) {
                $ageBonus = 10;
            } elseif ($post->published_at && $post->published_at >= now()->subDays(7)) {
                $ageBonus = 5;
            }

            $post->score = ($comments24h * 5) + ($comments7d * 3) + ($totalComments * 1) + $ageBonus;

            return $post;
        });

        // Skora göre sırala ve ilk 5
        return $result->sortByDesc('score')->take(5)->values();
    }
}
