<?php

use App\Models\User;
use App\Livewire\HomePage;
use App\Livewire\Client\Profile;
use App\Livewire\CreateEntreprise;
use App\Livewire\Client\HomeClient;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Payment\PricingPlan;
use Illuminate\Support\Facades\Route;
use App\Livewire\Payment\FactureClient;
use App\Livewire\Client\CreateInstances;
use App\Livewire\Payment\PaymentProcess;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\OtpVerificationController;

// Page d'accueil publique
Route::get('/', HomePage::class)->name('homepage');

// Routes d'authentification (pour les invités)
Route::get('inscription', [RegisterController::class, 'showRegistrationForm'])
    ->name('inscription');
Route::post('inscription', [RegisterController::class, 'inscription']);

// Routes pour la vérification OTP
Route::middleware(['auth'])->group(function () {
    Route::get('verify-otp', [OtpVerificationController::class, 'show'])
        ->name('verification.notice');
    Route::post('verify-otp', [OtpVerificationController::class, 'verify'])
        ->name('verification.verify');
    Route::post('verify-otp/resend', [OtpVerificationController::class, 'resend'])
        ->name('verification.resend');
});

// Routes pour les utilisateurs authentifiés et vérifiés
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    // Entreprise
    Route::get('/entreprise/create', CreateEntreprise::class)
        ->name('entreprise.create')
        ->middleware('profile.complete');

    // Plans et paiements
    Route::middleware(['has.entreprise'])->group(function () {
        Route::get('/offres/plans', PricingPlan::class)
            ->name('plans.selection');

        Route::get('/payment/process/{uuid}', PaymentProcess::class)
            ->middleware('plan.flow')
            ->name('payment.process');
    });

    // Instance
    Route::middleware(['has.entreprise', 'plan.flow', 'instance.limit'])->group(function () {
        Route::get('/instance/create', CreateInstances::class)
            ->name('instance.create');
    });

    // Espace client
    Route::middleware(['role:client', 'has.entreprise', 'has.instance'])
        ->prefix('client-espace')
        ->group(function () {
            Route::get('/client', HomeClient::class)
                ->name('espaceClient');
            Route::get('/instances', HomeClient::class)
                ->name('instances.list');
        });
});
