<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
{
    $user = auth()->user();

    if (!$user) {
        return response()->json(['message' => 'User not authenticated'], 403);
    }

    if (!$user->hasRole($role)) {
        // Add this line to see the user's roles in the log
        \Log::info('User roles: ' . json_encode($user->getRoleNames()));
        
        return response()->json(['message' => 'User does not have the right roles.'], 403);
    }

    return $next($request);
}

}
