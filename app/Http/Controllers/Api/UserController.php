<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
{
    $users = User::with('roles')->get()->map(function ($user) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->roles->first()?->name ?? 'user',
            'status' => $user->active ? 'active' : 'inactive',
            'created_at' => $user->created_at->format('d M Y'),
            'created_ago' => $user->created_at->diffForHumans(),
        ];
    });

    return response()->json([
        'success' => true,
        'message' => 'Data user berhasil diambil',
        'data' => $users
    ]);
}

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'active' => true,
        ]);

        $user->assignRole($request->role);

        return response()->json([
            'message' => 'User berhasil dibuat',
            'user' => $user->fresh()->load('roles')
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,$id",
            'role' => 'required|exists:roles,name',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $user->syncRoles([$request->role]);

        return response()->json([
            'message' => 'User berhasil diperbarui',
            'user' => $user->load('roles')
        ]);
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['message' => 'User berhasil dihapus']);
    }

    public function getRoles()
    {
        return response()->json(Role::pluck('name'));
    }
}