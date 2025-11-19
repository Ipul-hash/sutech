<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display list user + filter role (Spatie)
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        // Filter role (Spatie)
        if ($request->role && $request->role !== '') {
            $query->whereHas('roles', function ($r) use ($request) {
                $r->where('name', $request->role);
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $users = $query->paginate(10);

        // Dashboard stats
        $stats = [
            'total' => User::count(),
            'admin' => User::role('admin')->count(),
            'staff' => User::role('staff')->count(),
            'user' => User::role('user')->count(),
        ];

        return view('users.index', compact('users', 'stats'));
    }

    /**
     * Store new user + assign role (Spatie)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|string|exists:roles,name'
        ]);

        // Create user
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign role Spatie
        $user->assignRole($request->role);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dibuat',
            'user' => $user
        ]);
    }

    /**
     * Show user detail
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'success' => true,
            'user' => $user,
            'roles' => $user->getRoleNames()
        ]);
    }

    /**
     * Update user + role
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|max:255',
            'email'    => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|min:6',
            'role'     => 'required|string|exists:roles,name'
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Update role
        $user->syncRoles([$request->role]);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diupdate'
        ]);
    }

    /**
     * Delete user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id == auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa menghapus akun sendiri'
            ], 403);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus'
        ]);
    }

    /**
     * Bulk delete user
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:users,id',
        ]);

        $ids = array_filter($request->ids, fn ($id) => $id != auth()->id());

        User::whereIn('id', $ids)->delete();

        return response()->json([
            'success' => true,
            'message' => count($ids) . ' user berhasil dihapus'
        ]);
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil direset'
        ]);
    }
}
