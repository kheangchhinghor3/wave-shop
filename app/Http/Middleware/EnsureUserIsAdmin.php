<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Only logged-in users with is_admin = true may continue
        if (! $request->user() || ! $request->user()->is_admin) {
            abort(403, 'Admins only.');
        }

        return $next($request);
    }
}