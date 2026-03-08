<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    // Admin tüm kullanıcıları görebilir
    public function index()
    {
        return User::select('id', 'first_name', 'last_name', 'email', 'phone', 'role')
            ->latest()
            ->get();
    }

    // Admin kullanıcı rolünü değiştirebilir
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,editor,user'
        ]);

        $user->update([
            'role' => $request->role
        ]);

        return response()->json([
            'message' => 'Role updated',
            'user' => $user
        ]);
    }

    // Admin kullanıcı silebilir
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => 'User deleted'
        ]);
    }
}
