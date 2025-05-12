<?php

namespace App\Providers;

use App\Services\ClientCodeGeneratorService;
use App\Services\DolibarrApiService;
use App\Services\EntrepriseApiService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(DolibarrApiService::class, function ($app) {
            return new DolibarrApiService(
                "https://www.gmg.erpinnov.com/api/index.php",
                "3at1TxcD44CYN4J9LJ23ldG6r7VrcdTu"
            );
        });

        $this->app->bind(ClientCodeGeneratorService::class,function($app){
            return new ClientCodeGeneratorService($this->app->make(DolibarrApiService::class));
        });

        $this->app->bind(EntrepriseApiService::class, function ($app) {
            return new EntrepriseApiService(
                $this->app->make(DolibarrApiService::class),
                $this->app->make(ClientCodeGeneratorService::class)
            );
        });

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
