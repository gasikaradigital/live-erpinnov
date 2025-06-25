<?php

namespace App\Services;

use App\Services\CpanelService;
use Illuminate\Support\Facades\Config;
use App\Jobs\CreateUserDolibarr;

class DolisassService
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
        
        $entreprise = Entreprise::find($request->entrepriseId);

        //Création utilisateur de dolibarr
        CreateUserDolibarr::dispatch($request->name, $entreprise);
        
        return [
            'url' => $urlSuffix . Config::get('dolibarr.domain_suffix')
        ];
    }
}
