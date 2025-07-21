<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\SubPlan;
use Illuminate\Support\Facades\Http;
use App\Models\Subscription;

class SubscriptionController extends Controller
{
    
    public function create(Request $request){
        /**
         *  @var User $user
         */
        $user  = auth()->user();

        $validator = Validator::make($request->all(), [
            'planId' => ['integer', 'required'],
            'sub_planId' => ['integer', 'required']
        ]); 

        if($validator->fails()){
            return response()->json($validator->errors(), 401);
        }
        try{
            // Créer l'abonnement
            Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $request->planId,
                'sub_plan_id' => $request->sub_planId,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'status' => Subscription::STATUS_TRIAL,
            ]);

            return response()->json([
                'Message' => 'subscription créer avec succès',
            ], 200);

        } catch (\Exception $e) {}
    }
}