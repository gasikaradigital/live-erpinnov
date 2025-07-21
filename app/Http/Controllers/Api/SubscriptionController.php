<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\SubPlan;
use Illuminate\Support\Facades\Http;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{
    
    public function create(Request $request){
        /**
         *  @var User $user
         */
        $user  = auth()->user();

        $validator = Validator::make($request->all(), [
            'planId' => ['integer', 'required'],
            'subPlanId' => ['integer', 'required']
        ]); 

        if($validator->fails()){
            return response()->json($validator->errors(), 401);
        }

        try{
            // Créer l'abonnement
            Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $request->planId,
                'sub_plan_id' => $request->subPlanId,
                'start_date' => Carbonn::now(),
                'end_date' => Carbon::now()->addDays(15),
                'status' => Subscription::STATUS_TRIAL,
            ]);

            return response()->json([
                'Message' => 'subscription créer avec succès',
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'erreur' => $e->getMessage()
            ]);
        }
    }
}