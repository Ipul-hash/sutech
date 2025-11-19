<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function tiket()
    {
        return view('admin.tiket');
    }

    public function kelolaUser()
    {
        $users = User::with(['roles', 'team', 'position'])->get();
        $roles = ['admin', 'agent', 'user'];
        return view('admin.kelola-user', compact('users', 'roles'));
    }

    // ======================================
    // CREATE USER
    // ======================================
    public function storeUser(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users',
            'password'    => 'required|min:5',
            'role'        => 'required|in:admin,agent,user',
            'status'      => 'required|in:active,inactive,suspended',
            'team_id'     => 'nullable|exists:teams,id',
            'position_id' => 'nullable|exists:positions,id',
        ]);

        $user = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => bcrypt($request->password),
            'active'      => $request->status === 'active',
            'team_id'     => $request->team_id,
            'position_id' => $request->position_id,
        ]);

        $user->assignRole($request->role);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil ditambahkan.',
            'user' => [
                'id'           => $user->id,
                'name'         => $user->name,
                'email'        => $user->email,
                'role'         => $user->roles->first()?->name ?? 'user',
                'status'       => $user->active ? 'active' : 'inactive',
                'team_id'      => $user->team_id,
                'team_name'    => $user->team->name ?? null,
                'position_id'  => $user->position_id,
                'position_name'=> $user->position->name ?? null,
                'created_at'   => $user->created_at->format('d M Y'),
                'created_ago'  => $user->created_at->diffForHumans(),
            ]
        ]);
    }

    // ======================================
    // UPDATE USER
    // ======================================
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => "required|email|unique:users,email,$id",
            'role'        => 'required|in:admin,agent,user',
            'status'      => 'required|in:active,inactive,suspended',
            'team_id'     => 'nullable|exists:teams,id',
            'position_id' => 'nullable|exists:positions,id',
        ]);

        $user->update([
            'name'        => $request->name,
            'email'       => $request->email,
            'active'      => $request->status === 'active',
            'team_id'     => $request->team_id,
            'position_id' => $request->position_id,
        ]);

        $user->syncRoles([$request->role]);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diperbarui.',
            'user' => [
                'id'           => $user->id,
                'name'         => $user->name,
                'email'        => $user->email,
                'role'         => $user->roles->first()?->name ?? 'user',
                'status'       => $user->active ? 'active' : 'inactive',
                'team_id'      => $user->team_id,
                'team_name'    => $user->team->name ?? null,
                'position_id'  => $user->position_id,
                'position_name'=> $user->position->name ?? null,
            ]
        ]);
    }

    // ======================================
    // DELETE USER
    // ======================================
    public function destroyUser($id)
    {
        User::findOrFail($id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus.'
        ]);
    }

    // ======================================
    // GET USER DATA FOR FE
    // ======================================
    public function getUserData()
    {
        $users = User::with(['roles', 'team', 'position'])
            ->get()
            ->map(fn($u) => [
                'id'            => $u->id,
                'name'          => $u->name,
                'email'         => $u->email,
                'role'          => $u->roles->first()?->name ?? 'user',
                'status'        => $u->active ? 'active' : 'inactive',
                'team_id'       => $u->team_id,
                'team_name'     => $u->team->name ?? null,
                'position_id'   => $u->position_id,
                'position_name' => $u->position->name ?? null,
                'created_at'    => $u->created_at->format('d M Y'),
                'created_ago'   => $u->created_at->diffForHumans(),
            ]);

        return response()->json($users);
    }
}
