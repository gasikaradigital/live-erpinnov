<?php

namespace App\Services;

use App\Models\Subdomain;
use App\Services\CpanelService;
use App\Services\DatabaseServiceInnov;
use App\Services\DatabaseServiceDolibarr;
use App\Services\EncryptApiService;
use Illuminate\Support\Facades\Config;

class InstanceProvisioningService
{
    private $cpanelService;
    private $databaseService;
    private $apiService;

    public function __construct()
    {
        $this->cpanelService = new CpanelService();
        $this->databaseServiceInnov = new DatabaseServiceInnov();
        $this->databaseServiceDolibarr = new DatabaseServiceDolibarr();
        $this->apiService = new EncryptApiService();
    }


    public function provisionInstance($instanceName, $password, $login, $urlSuffix, $api_key_dolibarr, $userEmail, $subscriptionId, $instance_free)
    {
        //Création base de donnée de Innov
        $dbNameInnov = $this->databaseServiceInnov->createDatabaseInnov($instanceName);
        if(!$dbNameInnov) {
            return false;
        }
        
        //Importation de base de donnée pour Innov
        $this->databaseServiceInnov->importDatabaseInnov($dbNameInnov);
        
        //Mise à jours base de donnée Innov
        $this->databaseServiceInnov->updateCredentialsInnov($dbNameInnov, $instanceName, $api_key_dolibarr, $password, $userEmail, $subscriptionId, $instance_free);
        
        ///Création sous-domaine pour Innov
        $this->cpanelService->createSubdomainInnov($instanceName);
        
        //Enregistrement du sous-domaine et base de donnée propre au instance crée qui est utilisé pour Innov
        Subdomain::create([
            'subdomain' => 'http://' . $instanceName . '.erpinnov.com',
            'database_name' => $dbNameInnov
        ]);
        
        return [
            'url' => $urlSuffix . Config::get('dolibarr.domain_suffix'),
            'db_name' => $dbNameInnov
        ];
    }
}
