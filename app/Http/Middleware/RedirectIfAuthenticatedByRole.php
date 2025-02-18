<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticatedByRole
{
   public function handle(Request $request, Closure $next): Response
   {
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();

            if (!$user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }

           if ($user->hasRole('client')) {
                // Vérifier si le profil est complet
                if (!$user->profile->isComplete() && !$request->routeIs('profile.edit')) {
                    return redirect()->route('profile.edit')
                        ->with('warning', 'Veuillez compléter votre profil.');
                }

               if (!$user->entreprises()->exists()) {
                   return redirect()->route('entreprise.create');
               }

               if (!session()->has('selected_plan')) {
                   return redirect()->route('plans.selection');
               }

               if (!$user->instances()->exists()) {
                   return redirect()->route('instance.create');
               }

               if (!$request->is('client-espace/*')) {
                   return redirect()->route('espaceClient');
               }
           }
       }

       return $next($request);
   }
}
