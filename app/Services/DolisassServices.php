<?php

namespace App\Services;

use Exception;
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


    public function provisionInstance($data)
    {
        //Création sous-domaine pour le client
        //$this->cpanelService->createSubdomainMg($request->name);
        try{
            $entreprise = Entreprise::find($data['entrepriseId']);
            if($entreprise) {
                Log::info("Entreprise bien récupérer");
            } else {
                return 'entreprise non troure';
            }
            
            return $entreprise;
            //Création utilisateur de dolibarr
            //CreateUserDolibarr::dispatch($request->name, $entreprise);
        } catch (\Exception $e) {
            Log::info("erreur dans le dolisassServices: " . $e->getMessage());
        }
        
        
    }
}
