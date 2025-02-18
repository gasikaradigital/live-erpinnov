<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfProfileIncomplete
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($request->routeIs('profile.*')) {
            return $next($request);
        }

        $profile = $user->profile;

        // Si le profil n'est pas complet, rediriger vers l'édition du profil
        if (!$profile->fname || !$profile->lname || !$profile->telephone) {
            return redirect()->route('profile.edit')
                ->with('warning', 'Veuillez compléter votre profil.');
        }

        return $next($request);
    }
}
