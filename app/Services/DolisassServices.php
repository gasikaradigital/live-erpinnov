<?php

namespace App\Services;

use App\Services\CpanelService;
use Illuminate\Support\Facades\Config;
use App\Jobs\CreateUserDolibarr;
use Illuminate\Support\Facades\Log;

class DolisassServices
{
    private $cpanelService;
    private $databaseService;
    private $apiService;

    public function __construct()
    {
        $this->cpanelService = new CpanelService();
    }


    public function provisionInstance($request)
    {
        //Création sous-domaine pour le client
        //$this->cpanelService->createSubdomainMg($request->name);
        try{
            $entreprise = Entreprise::find($request->entrepriseId);
            if(!$entreprise) {
                Log::error("erreur lors de la récupération de l'entreprise: ");
            }
            //Création utilisateur de dolibarr
            CreateUserDolibarr::dispatch($request->name, $entreprise);
        } catch (\Exception $e) {
            Log::error("erreur dans le dolisassServices: " . $e->getMessage());
        }
        
        
    }
}
