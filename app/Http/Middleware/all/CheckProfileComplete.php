<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckProfileComplete
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        $profile = $user->profile;

        // Vérifier si le profil est complet
        if (!$profile->fname || !$profile->lname || !$profile->telephone) {
            return redirect()->route('profile.edit')
                ->with('warning', 'Veuillez compléter votre profil avant de continuer.');
        }

        return $next($request);
    }
}
