<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return $user->role === 'admin' || $user->role  === 'editor';
    }
    public function view(User $user)
    {
        return $user->role === 'admin' || $user->role  === 'editor';
    }

    public function create(User $user)
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Category $category)
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Category $category)
    {
        return $user->role === 'admin';
    }
}
