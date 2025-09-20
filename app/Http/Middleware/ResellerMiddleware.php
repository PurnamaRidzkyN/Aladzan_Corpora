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
            $user = Auth::guard('reseller')->user();

            if ($user && is_null($user->plan_id)) {
                if (!in_array($request->route()->getName(), ['upgrade.account', 'upgrade.account.payment', 'upgrade.account.payment.store', 'check.discount'])) {
                    return redirect()->route('upgrade.account')->with('error', 'Kamu belum memiliki plan. Silahkan pilih plan terlebih dahulu atau tunggu admin konfirmasi.');
                }
            }

            return $next($request);
        }

        return redirect()->route('login.reseller')->with('error', 'Akses hanya untuk reseller.');
    }
}
