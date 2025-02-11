<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Subscription;
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
        $hasTrialInstance = $user->subscriptions()
            ->where('status', Subscription::STATUS_TRIAL)
            ->exists();

        if ($request->routeIs('instance.create')) {
            // Si c'est une période d'essai et déjà une instance
            if ($hasTrialInstance && $instanceCount >= 1) {
                return redirect()->route('plans.selection');
            }

            // Vérifier limite d'instances pour plan non-trial
            if (!$hasTrialInstance && $currentPlan && $instanceCount >= $currentPlan->instance_limit) {
                session()->flash('error', 'Limite d\'instances atteinte');
                return redirect()->route('espaceClient');
            }
        }

        return $next($request);
    }
}
