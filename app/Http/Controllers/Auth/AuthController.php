<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\AuthIdentity;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // --- LOGIN/LOGOUT ENDPOINTS ---
    /**
     * POST /api/login
     * body: { type: 'email'|'username'|'room_no', identifier: string, password: string }
     * returns: { token, user }
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $type       = $request->string('type');
        $identifier = $request->string('identifier');
        $password   = $request->string('password');

        // prefetch permissions, retrieve AuthIdentity if exists
        $identity = AuthIdentity::with('user.role.permissions')
            ->where('type', $type)
            ->where('identifier', $identifier)
            ->first();

        // wrong / missing password, no matching identity
        if (! $identity || ! Hash::check($password, $identity->password_hash)) {
            return response()->json(['message' => 'Invalid Credentials'], 401);
        }

        // mark last login
        $identity->forceFill(['last_login_at' => now()])->save();

        // create Sanctum token
        $token = $identity->user->createToken("auth:$type")->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => new UserResource($identity->user),
        ]);
    }

    /**
     * POST /api/logout
     * body: {}
     * returns: {}
     */
    public function logout(): JsonResponse
    {
        request()->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out.']);
    }
}
