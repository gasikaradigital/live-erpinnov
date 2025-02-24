<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfProfileIncomplete
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        
        if (!$user->profile || !$user->profile->isComplete()) {
            return redirect()->route('profile.edit')
                ->with('warning', 'Veuillez compléter votre profil avant de continuer.');
        }

        return $next($request);
    }
}