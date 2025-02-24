<?php

namespace App\Services;

use App\Models\Subdomain;
use App\Services\CpanelService;
use App\Services\DatabaseServiceInnov;
use App\Services\DatabaseServiceDolibarr;
use App\Services\FileService;
use App\Services\EncryptApiService;
use Illuminate\Support\Facades\Config;

class InstanceProvisioningService
{
    private $cpanelService;
    private $databaseService;
    private $fileService;
    private $apiService;

    public function __construct()
    {
        $this->cpanelService = new CpanelService();
        $this->databaseServiceInnov = new DatabaseServiceInnov();
        $this->databaseServiceDolibarr = new DatabaseServiceDolibarr();
        $this->fileService = new FileService();
        $this->apiService = new EncryptApiService();
    }


    public function provisionInstance($instanceName, $password, $login, $urlSuffix, $api_key_dolibarr, $userEmail, $subscriptionId)
    {
        //Création base de donnée de dolibarr
        $dbName = $this->databaseServiceDolibarr->createDatabase($instanceName);
        if (!$dbName) {
            return false;
        }
        
        //Création base de donnée de Innov
        $dbNameInnov = $this->databaseServiceInnov->createDatabaseInnov($instanceName);
        if(!$dbNameInnov) {
            return false;
        }
        
        //Importation de base de donnée pour Dolibarr
        $this->databaseServiceDolibarr->importDatabase($dbName);
        
        //Importation de base de donnée pour Innov
        $this->databaseServiceInnov->importDatabaseInnov($dbNameInnov);
        
        //Cryptage API key dolibarr
        $instance_id = "ad62ff0728deff79f830a2b69cf68aae";
        $api_key = $this->apiService->dolEncryptApi($api_key_dolibarr, $instance_id);
        
        //Mise à jours base de donnée Dolibarr
        $this->databaseServiceDolibarr->updateCredentials($login, $dbName, $password, $api_key);
        
        
        //Mise à jours base de donnée Innov
        $this->databaseServiceInnov->updateCredentialsInnov($dbNameInnov, $instanceName, $api_key_dolibarr, $password, $userEmail, $subscriptionId);
        
        //Création sous-domaine pour Dolibarr
        $documentRoot = $this->cpanelService->createSubdomain($instanceName);
        if (!$documentRoot) {
            return false;
        }
        
        ///Création sous-domaine pour Innov
        $this->cpanelService->createSubdomainInnov($instanceName);

        //Copie fichier d'installation DOolibarr
        $this->fileService->copyDolibarrFiles($documentRoot);
        
        //Mise à jours fichier de configuration de Dolibarr
        $this->fileService->updateConfiguration($documentRoot, $instanceName, $dbName);
        
        
        //Enregistrement du sous-domaine et base de donnée propre au instance crée qui est utilisé pour Innov
        Subdomain::create([
            'subdomain' => 'http://' . $instanceName . '.erpinnov.com',
            'database_name' => $dbNameInnov
        ]);
        
        return [
            'url' => $urlSuffix . Config::get('dolibarr.domain_suffix'),
            'document_root' => $documentRoot,
        ];
    }
}
