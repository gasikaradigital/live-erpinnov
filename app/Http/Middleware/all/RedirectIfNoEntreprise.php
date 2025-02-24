<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfNoEntreprise
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();

            // Si l'utilisateur n'a pas vérifié son email, le laisser continuer
            if (!$user->hasVerifiedEmail()) {
                return $next($request);
            }

            // Si on est sur la page de création d'entreprise, laisser continuer
            if ($request->routeIs('entreprise.create')) {
                return $next($request);
            }

            // Si l'utilisateur n'a pas d'entreprise, rediriger
            if (!$user->entreprises()->exists()) {
                return redirect()->route('entreprise.create')
                    ->with('warning', 'Veuillez créer une entreprise avant de continuer.');
            }
        }

        return $next($request);
    }
}
