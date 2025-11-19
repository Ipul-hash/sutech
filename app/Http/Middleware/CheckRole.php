<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        // harus login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // kalau user tidak punya role
        if (!auth()->user()->hasRole($role)) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
