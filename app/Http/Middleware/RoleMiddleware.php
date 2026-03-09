<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if (! in_array($request->user()->role, $roles)) {
            $role = $request->user()->role;
            return redirect()->route("{$role}.dashboard")->with('error', 'Accès non autorisé.');
        }

        return $next($request);
    }
}
