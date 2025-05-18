<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FAQController;
use App\Http\Controllers\Api\InstancesController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\SheetWebHook;
use App\Http\Controllers\Api\TestDolibarrApi;
use App\Http\Controllers\Api\TutorialController;
use App\Http\Controllers\Auth\OtpVerificationController;
use App\Http\Controllers\Api\TicketsController;
use App\Models\User;
use Fruitcake\Cors\HandleCors;
use Illuminate\Support\Facades\Log;

Route::get('/sanctum/csrf-cookie', function () {
    return response()->json(['csrf_cookie' => 'set']);
});

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();

    return response()->json(['Message' => 'Déconnexion réussie']);
})->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'register']);

Route::post('/send-reset-link',[AuthController::class,'sendResetLink']);
Route::post('/reset-password',[AuthController::class,'resetPassword']);

// Routes protégées par authentification sanctum, toutes les routes qui nécessite le controle d'authentification doivent-être ici
Route::middleware(['auth:sanctum'])->group(function () {
    // Vérification OTP
    Route::post('/verify-otp', [OtpVerificationController::class, 'verify']);
    Route::post('/resend-otp', [OtpVerificationController::class, 'resend']);

    //Renvoie des plans et subplans
    Route::get('/plans', [PlanController::class, 'plan']);

    // Recuperation de Profile
    Route::patch('profile', [ProfileController::class, 'update']);


    // Récuperation de l'utilisateur
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Récuperation des instances liés au utilisateur
    #Route::get('instances',[InstancesController::class,'getInstanceByUser']);

    // Création d'instance
    #Route::post('instances',[InstancesController::class,'createInstance']);

    //Récupération de tous les tickets
    Route::get('/tickets/get', [TicketsController::class, 'getTickets']);

});
Route::get('/faq', [FAQController::class, 'getAll']);
Route::get('/tutorial', [TutorialController::class, 'getAll']);

Route::group(['middleware' => 'verify-apps-script'], function () {
    Route::post('webhooks/tutorial', [TutorialController::class, 'receive']);
    Route::post('/webhooks/faq', [FAQController::class, 'receive']);
});

Route::post('/entreprises',[TestDolibarrApi::class,'testCreateEntreprise']);
Route::get('/entreprises',[TestDolibarrApi::class,'getEntreprises']);