<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNoEntreprise
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->hasVerifiedEmail()) {
            if (Auth::user()->entreprises()->count() == 0 && !$request->is('entreprise/create')) {
                return redirect()->route('entreprise.create');
            }
        }

        return $next($request);
    }
}
