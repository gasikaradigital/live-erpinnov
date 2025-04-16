<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\SubPlan;

class PlanController extends Controller
{
        /**
     * Returns a JSON response containing all plans and sub-plans.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function plan()
    {
        $user = auth()->user();

        $plans = Plan::all();
        $subPlans = SubPlan::all();

        return response()->json([
            'plans' => $plans,
            'subPlans' => $subPlans,
        ]);
    }
}
