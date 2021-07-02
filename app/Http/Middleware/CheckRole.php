<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        if ($role == 'admin' && auth()->user()->role_id != 1) {
            abort(403);
        }

        if ($role == 'hap' && auth()->user()->role_id != 2) {
            abort(403);
        }

        if ($role == 'professor' && auth()->user()->role_id != 3) {
            abort(403);
        }

        return $next($request);
    }
}
