<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DolisassServices;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class DolisassController extends Controller
{
    public function createUserSass(Request $request){

        //Validation des données
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'entrepriseId' => ['required'],
        ]);

        if ($validator->fails()) {
            Log::info("Erreur des données reçu");
            return response()->json($validator->errors(), 422);
            
        }


        try{
            $dolisass = new DolisassServices();
            $entreprise  = $dolisass->provisionInstance($request->all());
            return response()->json([
                'entreprise' => $entreprise,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'Message' => 'enregistrement annulé',
                'erreur' => $e->getMessage(),
            ]);
        }
    } 
}
