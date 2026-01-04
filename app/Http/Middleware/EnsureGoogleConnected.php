<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EnsureGoogleConnected
{
    public function handle($request, Closure $next)
    {
        // $employee = Auth::user()->employee ?? null;

        // if (!$employee) {
        //     return redirect()->route('home');
        // }

        // $googleAccount = $employee->googleAccount;

        // if (!$googleAccount) {
        //     return redirect()->route('google.connect', $employee->id);
        // }

        // if (Carbon::now()->gte($googleAccount->token_expires_at)) {
        //     return redirect()->route('google.connect', $employee->id);
        // }

        // return $next($request);
        $employee = Auth::user()->employee;

        if (!$employee) {
            return redirect()->route('home');
        }

        $googleAccount = $employee->googleAccount;

        if (!$googleAccount || !$googleAccount->refresh_token) {
            return redirect()->route('google.connect', $employee->id);
        }

        return $next($request);
    }
}
