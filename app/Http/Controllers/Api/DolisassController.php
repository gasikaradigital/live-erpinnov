<?php

namespace App\Http\Controllers\Api;

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
            return response()->json($validator->errors(), 422);
            Log::info("Erreur des données reçu");
        }

        try{
            $dolisass = new DolisassServices($request->all());
        } catch (\Exception $e) {
            return response()->json(['Message' => 'enregistrement annulé']);
            Log::error("erreur: " . $e->getMessage());
        }
    } 
}
