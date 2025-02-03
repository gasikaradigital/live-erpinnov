<?php

use App\Models\User;
use App\Livewire\HomePage;
use App\Livewire\Client\Profile;
use App\Livewire\CreateEntreprise;
use App\Livewire\Client\HomeClient;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Payment\PricingPlan;
use Illuminate\Support\Facades\Route;
use App\Livewire\Client\InstanceListes;
use App\Livewire\Payment\FactureClient;
use App\Livewire\Client\CreateInstances;
use App\Livewire\Payment\PaymentProcess;
use App\Livewire\Payment\PlansSelection;

// Page d'accueil publique
Route::get('/', HomePage::class)->name('homepage');

// Redirection après authentification

Route::get('/redirect', function () {
    /** @var User $user */
    $user = Auth::user();

    if (!$user->hasVerifiedEmail()) {
        return redirect()->route('verification.notice');
    }

    if ($user->hasRole('client')) {
        // Vérification séquentielle
        if (!$user->entreprises()->exists()) {
            return redirect()->route('entreprise.create');
        }

        if (!session()->has('selected_plan')) {
            return redirect()->route('plans.selection');
        }

        if (!$user->instances()->exists()) {
            return redirect()->route('instance.create');
        }

        return redirect()->route('espaceClient');
    }

    abort(404, 'Page not found');
})->name('redirect');


// Routes protégées nécessitant une authentification
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {

    // Routes pour la création d'entreprise et sélection de plan
    Route::get('/entreprise/create', CreateEntreprise::class)
        ->name('entreprise.create');

    Route::get('/offres/plans', PricingPlan::class)
        ->middleware('has.entreprise')
        ->name('plans.selection');

Route::middleware(['has.entreprise', 'instance.limit'])->group(function () {
    Route::get('/instance/create', CreateInstances::class)
        ->middleware('plan.flow')
        ->name('instance.create');

    Route::get('/payment/process/{uuid}', PaymentProcess::class)
        ->middleware('plan.flow')  // Ajoutez cette ligne
        ->name('payment.process');
});

    // Routes pour l'espace client
    Route::middleware(['role:client', 'has.entreprise'])->prefix('client-espace')->group(function () {
        // Page d'accueil de l'espace client
        Route::get('/client', HomeClient::class)
            ->name('espaceClient');

        // Gestion des factures
        Route::get('/facturation', FactureClient::class)
            ->name('client.facture');

        Route::get('/instances', InstanceListes::class)
            ->name('instances.list');

        // Profil utilisateur
        Route::get('/profile', Profile::class)
            ->name('client.profile');
    });
});
