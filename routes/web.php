<?php

use App\Livewire\HomePage;
use App\Livewire\Client\Profile;
use App\Livewire\Client\HomeClient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\Client\FactureClient;
use App\Livewire\Client\InstanceListes;
use App\Livewire\Client\PaymentProcess;
use App\Livewire\Client\CreateInstances;
use App\Livewire\CreateEntreprise;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;


Route::get('/', HomePage::class)->name('homepage');


// roles redirect
Route::get('/redirect', function () {
    $user = Auth::user();

    if ($user->hasRole('client')) {
        return redirect()->route('espaceClient');
    }

    return redirect()->route('404');

})->middleware(['auth', 'verified']);

// routes listes
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {

    // Route for Client
    Route::middleware(['role:client', 'has.entreprise'])->prefix('client-espace')->group(function () {

        Route::get('/client', HomeClient::class)->name('espaceClient');

        Route::get('/facturation', FactureClient::class)->name('client.facture');

        Route::get('/instance/create', CreateInstances::class)->name('instance.create');
        Route::get('/instances', InstanceListes::class)->name('instances.list');

        Route::get('/payment/process/{uuid}', PaymentProcess::class)->name('payment.process');

         Route::get('/profile', Profile::class)->name('client.profile');

    });


    Route::get('/entreprise/create', CreateEntreprise::class)->name('entreprise.create');

});

