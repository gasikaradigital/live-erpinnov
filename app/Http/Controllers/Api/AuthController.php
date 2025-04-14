<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Notifications\OtpVerification;
use Illuminate\Auth\Events\Registered;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function register(Request $request){
        //Validation des données
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['required', 'accepted'],
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
        try{
            \DB::beginTransaction();

            // Créer l'utilisateur
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_active' => true,
            ]);

            
            // Créer un profil vide
            $user->profile()->create([
                'is_public' => true
            ]);

            // Assigner le rôle client
            $roleClient = Role::firstOrCreate(['name' => 'client']);
            $user->assignRole($roleClient);

             // Générer l'OTP
             $otp = rand(100000, 999999);
             $user->update([
                 'otp' => $otp,
                 'otp_expires_at' => now()->addMinutes(10),
             ]);
 
             // Envoyer l'OTP par email
             $user->notify(new OtpVerification($otp));
 
             event(new Registered($user));

             \DB::commit();

             //Création du token
            $token = $user->createToken('auth_token')->plainTextToken;

            // Lancer le Job d'ajout dans le contact de dolibarr
            try{
                (new AddInDolibarr($request->email))->handle();
            } catch(\Exception $e){
                dd($e->getMessage());
            } 

            return response()->json([
                'Message' => 'Utilisateur enregistré avec succès',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ], 201);
        } catch(\Exception $e){
            return response()->json(['Message' => 'enregistrement annulé']);
        }

        
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if(! $user || ! Hash::check($request->password, $user->password)){
            return response()->json([
                'message' => 'Identifiant invalides',
            ], 401);
        } 
    
        //Création du token
        $token = $user->createToken('auth_token')->plainTextToken;
    
        return response()->json([
            'acces_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);

    }
}
