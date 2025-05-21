<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EntrepriseController extends Controller
{
    public function create(Request $request){
        //Validation des données
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'nif' => ['required', 'string', 'min:8', 'confirmed'],
            'ville' => ['required', 'string', 'min:3', 'max:50'],
            'pays' => ['required', 'string', 'min:3', 'max:50'],
            'phone' => ['required', 'string', 'min:8', 'max:15'],
            'adresse' => ['required', 'string', 'min:3', 'max:100'],
            'employees_count' => ['required', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        try{
            $entreprise = auth()->user()->entreprises()->create([
                'name' => $request->name,
                'nif' => $request->nif,
                'ville' => $request->ville,
                'pays' => $request->pays ?? '',
                'phone' => $request->phone,
                'adresse' => $request->adresse,
                'employees_count' => $request->employees_count
            ]);

            // Mettre à jour la collection d'entreprises
            $this->entreprises = auth()->user()->entreprises()->get();

            return response()->json([
                'Message' => 'Entreprise créée avec succès',
            ], 201);
        } catch(\Exception $e){
            return response()->json([
                'Erreur' => $e->getMessage(),
            ]);
        }
        
    }
}
