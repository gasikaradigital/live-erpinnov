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
use App\Http\Controllers\Api\EntrepriseController;
use App\Http\Controllers\Api\DolisassController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Services\CreateUsersDolibarr;


Route::get('/sanctum/csrf-cookie', function () {
    return response()->json(['csrf_cookie' => 'set']);
});

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', function (Request $request) {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return response()->json(['message' => 'Logged out']);
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
        return response()->json([
            'user' => $request->user(),
            'profile' => $request->user()->profile,
            'entreprises' => $request->user()->entreprises,
            'subscriptions' => $request->user()->subscriptions
        ], 200);
    });


    // Récuperation des instances liés au utilisateur
    #Route::get('instances',[InstancesController::class,'getInstanceByUser']);

    // Création d'instance
    Route::post('/create-instance',[InstancesController::class,'createInstance']);

    //Récupération de tous les tickets
    Route::get('/tickets', [TicketsController::class, 'getTickets']);

    // Création ticket
    Route::post('/tickets',[TicketsController::class,'create']);

    Route::put('/tickets/{id}', [TicketsController::class, 'update']);
    Route::delete('/tickets/{id}', [TicketsController::class, 'delete']);

    //Création entreprise
    Route::post('/create-entreprise', [EntrepriseController::class, 'create']);

    Route::get('/entreprises',[EntrepriseController::class,'get']);

    //Création subscription
    Route::post('/create-subscription', [SubscriptionController::class, 'create']);

    //Récupérationd des plans
    Route::get('/plans', [PlanController::class, 'getFromDatabase']);
    
});
Route::get('/faq', [FAQController::class, 'getAll']);
Route::get('/tutorial', [TutorialController::class, 'getAll']);

Route::group(['middleware' => 'verify-apps-script'], function () {
    Route::post('webhooks/tutorial', [TutorialController::class, 'receive']);
    Route::post('/webhooks/faq', [FAQController::class, 'receive']);
});

Route::post('/tickets', [TicketsController::class, 'fetchFromDolibarr']);
