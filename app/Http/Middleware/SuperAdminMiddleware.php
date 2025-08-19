<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // app/Http/Middleware/ResellerMiddleware.php
    public function handle($request, Closure $next)
    {
        if (Auth::guard('admin')->check()) {
            if (Auth::guard('admin')->user()->is_super_admin) {
                return $next($request);
            }
        }

        return redirect()->route('dashboard.admin')->with('error', 'Akses hanya untuk Superadmin.');
    }
}
