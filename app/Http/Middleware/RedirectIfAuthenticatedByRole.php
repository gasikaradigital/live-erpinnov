<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class RedirectIfAuthenticatedByRole
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            /** @var User $user */
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
