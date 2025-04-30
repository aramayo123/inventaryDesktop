<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckLicenseValidity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user && Carbon::parse($user->licencia_expires_at)->isPast()) {
            Auth::logout();

            return redirect()->route('login')->withErrors([
                'email' => 'Tu licencia ha expirado. Por favor, renueva para continuar.',
            ]);
        }

        return $next($request);
    }
}
