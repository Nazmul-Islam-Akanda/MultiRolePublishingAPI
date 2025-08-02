<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        
        if (User::count() === 1) {
            $adminRole = Role::where('name', 'admin')->first();
            $user->roles()->attach($adminRole);
        }

        $role = $user->roles()->pluck('name')->first();
        $role = $role ?? 'unassigned';
        return response()->json([
            'success' => true,
            'message' => 'User registered successfully.',
            'data' => [
                'token' => $user->createToken('API Token')->plainTextToken,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $role,
                ],
            ],
        ], 201);

    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!auth()->attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = auth()->user();
        if (!$user->roles()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. Your account does not have a role assigned.',
                'error' => 'ROLE_NOT_ASSIGNED',
            ], 403);
        }
        $role = $user->roles()->pluck('name')->first() ?? 'unassigned';
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $role,
            ],
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'success' => true,
            'message' => 'You have been logged out successfully.',
        ], 200);
    }

}
