<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DolisassServices;
use Illuminate\Support\Facades\Validator;

class DolisassController extends Controller
{
    public function createUserSass(Request $request){

        //Validation des données
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'entrepriseId' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try{
            $dolisass = new DolisassServices($request->all());
        } catch (\Exception $e) {
            return response()->json(['Message' => 'enregistrement annulé']);
        }
    } 
}
