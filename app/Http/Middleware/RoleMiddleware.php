<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = auth()->user();

        // Not logged in
        if (!$user) {
            abort(403);
        }

        // User has no role
        if (!$user->role) {
            abort(403, 'No role assigned');
        }

        // Role mismatch
        if (!in_array($user->role->slug, $roles)) {
            // Allow super-admin to access everything
            if ($user->role->slug === 'super-admin') {
                return $next($request);
            }
            abort(403);
        }

        return $next($request);
    }
}
