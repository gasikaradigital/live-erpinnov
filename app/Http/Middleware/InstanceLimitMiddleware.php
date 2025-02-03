<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InstanceLimitMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $currentPlan = $user->activePlan();
        $instanceCount = $user->instances()->count();

        // Si l'utilisateur est sur la route de création d'instance
        if ($request->routeIs('instance.create')) {
            // Si pas de plan actif
            if (!$currentPlan) {
                session()->flash('warning', 'Veuillez sélectionner un plan avant de créer une instance.');
                return redirect()->route('plans.selection');
            }

            // Vérifier la limite d'instances
            if ($instanceCount >= $currentPlan->instance_limit) {
                session()->flash('error', sprintf(
                    'Vous avez atteint la limite de %s instance%s de votre plan %s.',
                    $currentPlan->instance_limit,
                    $currentPlan->instance_limit > 1 ? 's' : '',
                    $currentPlan->name
                ));
                return redirect()->route('instances.list');
            }
        }

        return $next($request);
    }
}
