<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        string $role
    ) {

        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $roles = [
            'super_admin' => 3,
            'admin' => 2,
            'user' => 1,
        ];

        if (
            !isset($roles[$user->role]) ||
            $roles[$user->role] < $roles[$role]
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied'
            ], 403);
        }

        return $next($request);
    }
}