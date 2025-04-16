<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\AuthController;
use App\Models\User;

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', function (Request $request){
    $request->user()->currentAccessToken()->delete();

    return response()->json(['Message' => 'Déconnexion réussie']);
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);

// Routes protégées par authentification sanctum, toutes les routes qui nécessite le controle d'authentification doivent-être ici
Route::middleware(['auth:sanctum', 'role:client|admin'])->group(function () {
    // Vérification OTP
    Route::post('/verify-otp', [OtpVerificationController::class, 'verify']);
    Route::post('/resend-otp', [OtpVerificationController::class, 'resend']);

    Route::get('/dashboard', function (){
        return response()->json(['Message' => 'Bienvenue sur votre dashboard']);
    });
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
