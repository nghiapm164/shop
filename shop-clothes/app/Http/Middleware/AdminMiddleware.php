<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Check if user is admin, super_admin, or staff
        if (!in_array($request->user()->role, ['admin', 'super_admin', 'staff'], true)) {
            abort(403, 'Unauthorized access to admin panel');
        }

        if ($request->user()->is_active === false) {
            abort(403, 'Tài khoản quản trị đang bị khóa');
        }

        return $next($request);
    }
}
