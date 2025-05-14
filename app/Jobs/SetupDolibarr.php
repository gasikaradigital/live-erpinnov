<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Services\DolibarrApiService;

class SetupDolibarr implements ShouldQueue
{
    use Queueable;

    public $baseUrl, $apiKey;
    /**
     * Create a new job instance.
     * 
     * @param string $baseUrl l'url de base de l'instance dolibarr à injecté l'organisation
     * @param string $apiKey l'api key de l'instance dolibarr à injecté l'organisation
     * @param array $data les données de l'organisation à injecté
     */
    public function __construct($baseUrl, $apiKey, $data)
    {
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $dolibarrApiService = new DolibarrApiService($this->baseUrl, $this->apiKey);
        try {
            // Attempt to modify the company setup using the Dolibarr API
            $result = $dolibarrApiService->modify("setup/company", $this->data);
            if (!$response->successful()) {
                Log::info('TErreur dans la modification');
            }
            Log::info('Tiers créer avec succès');
        } catch (Exception $e) {
            // Return an error JSON response if an exception occurs
            Log::info('Erreur dans la modification' . $e->getMessage());
            }
    }
}
