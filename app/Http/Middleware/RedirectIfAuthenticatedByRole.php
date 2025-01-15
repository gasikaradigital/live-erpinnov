<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticatedByRole
{
    public function handle(Request $request, Closure $next)
    {
        // Si la requête attend du JSON, on ne redirige pas
        if ($request->expectsJson()) {
            return $next($request);
        }

        if (Auth::check()) {
            $user = Auth::user();

            if ($user->hasRole('client')) {
                if (!$user->entreprises()->exists()) {
                    return redirect()->route('entreprise.create');
                }
                return redirect()->route('espaceClient');
            }
        }

        return $next($request);
    }
}
