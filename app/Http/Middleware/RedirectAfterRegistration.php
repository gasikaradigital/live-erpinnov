<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectAfterRegistration
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();

            if ($user->hasRole('client') && !$user->entreprises()->exists()) {
                return redirect()->route('entreprise.create');
            }
        }

        return $next($request);
    }
}
