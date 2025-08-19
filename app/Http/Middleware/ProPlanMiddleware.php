<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProPlanMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $reseller = Auth::guard('reseller')->user();
        if (!$reseller || $reseller->plan->name !== 'Pro') {
            return redirect()->route('upgrade.account')
                ->with('error', 'Akses hanya untuk reseller plan PRO.');
        }

        return $next($request);
    }
}