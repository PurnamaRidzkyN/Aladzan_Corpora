<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ResellerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // app/Http/Middleware/ResellerMiddleware.php
    public function handle($request, Closure $next)
    {
        if (Auth::guard('reseller')->check()) {
            return $next($request);
        }

        return redirect()->route('login.reseller')->with('error', 'Akses hanya untuk reseller.');
    }
}
