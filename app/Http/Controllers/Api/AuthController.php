<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Notifications\OtpVerification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        //Validation des données
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['required', 'accepted'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        try {
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
            try {
                (new AddInDolibarr($request->email))->handle();
            } catch (\Exception $e) {
                dd($e->getMessage());
            }

            return response()->json([
                'Message' => 'Utilisateur enregistré avec succès',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['Message' => 'enregistrement annulé']);
        }
    }

    /**
     * Handle user login
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        // Create a new token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return match ($status) {
            Password::RESET_LINK_SENT => response()->json([
                'message' => 'Le lien de réinitialisation a été envoyé à votre adresse email.',
            ], 200),
    
            Password::INVALID_USER => response()->json([
                'message' => 'Aucun utilisateur n’a été trouvé avec cet email.',
            ], 404),
    
            Password::RESET_THROTTLED => response()->json([
                'message' => 'Trop de tentatives. Veuillez réessayer plus tard.',
            ], 429),
    
            default => response()->json([
                'message' => 'Une erreur est survenue.',
            ], 500),
        };
    }



    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return match ($status) {
            Password::PASSWORD_RESET => response()->json([
                'message' => 'Mot de passe réinitialisé avec succès.',
            ], 200),
    
            Password::INVALID_TOKEN => response()->json([
                'message' => 'Le lien de réinitialisation est invalide ou a expiré.',
            ], 400),
    
            Password::INVALID_USER => response()->json([
                'message' => 'Aucun utilisateur trouvé avec cet email.',
            ], 404),
    
            default => response()->json([
                'message' => 'Impossible de réinitialiser le mot de passe.',
            ], 500),
        };
    }
}
