<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    // --- WHOAMI ENDPOINT ---
    /**
     * GET /api/me
     * body: {}
     * returns: { user }
     */
    public function me(): JsonResponse
    {
        $user = request()->user()->load('role.permissions');
        return new UserResource($user);
    }
}