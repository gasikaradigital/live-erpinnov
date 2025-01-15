<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema; // N'oubliez pas d'importer cette classe

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Ajoutez cette ligne pour définir la longueur par défaut des chaînes
        Schema::defaultStringLength(191);
    }
}
