<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function users()
    {
        $this->authorize('viewAllUsers');

        $users = User::with('roles')->get();

        return response()->json([
            'success' => true,
            'message' => 'Users retrieved successfully.',
            'data' => $users,
        ], 200);
    }

    public function roles()
    {
        $this->authorize('viewAllUsers');

        $roles = Role::all();

        return response()->json([
            'success' => true,
            'message' => 'Roles retrieved successfully.',
            'data' => $roles,
        ], 200);
    }

    public function assignRole(Request $request, $id)
    {
        $this->authorize('assignRoles');

        $user = User::findOrFail($id);
        $role = Role::where('name', $request->role)->first();
        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'The specified role does not exist.',
                'error' => 'ROLE_NOT_FOUND',
            ], 404);
        }
        $user->roles()->sync([$role->id]);

        return response()->json([
            'success' => true,
            'message' => 'Role has been assigned successfully.',
        ], 200);
    }

    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'User retrieved successfully.',
            'data' => $request->user()->load('roles'),
        ], 200);
    }
    
}
