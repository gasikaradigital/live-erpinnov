<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PlanFlowMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Vérifier si un plan a été sélectionné
        if (!session()->has('selected_plan')) {
            return redirect()->route('plans.selection');
        }

        $selectedPlan = session()->get('selected_plan');

        // Si on est sur la route de création d'instance
        if ($request->routeIs('instance.create')) {
            // Si c'est un plan payant et qu'il n'y a pas de paiement
            if (!$selectedPlan['is_free'] && !session()->has('payment_completed')) {
                return redirect()->route('payment.process', ['uuid' => $selectedPlan['uuid']]);
            }
        }

        // Si on est sur la route de paiement
        if ($request->routeIs('payment.process')) {
            // Si c'est un plan gratuit, rediriger vers la création d'instance
            if ($selectedPlan['is_free']) {
                return redirect()->route('instance.create');
            }
        }

        return $next($request);
    }
}
