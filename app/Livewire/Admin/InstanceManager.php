<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\InstanceQuota;
use App\Livewire\Admin\ChangeConfigInstance;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Services\EcryptApiService;

class InstanceManager extends Component
{
    use LivewireAlert;

    public $id, $url, $password, $apiKey, $dbName, $dbUser, $dbPass, $instanceId, $prefix, $statut = 'libre';
    public $libres, $attribues;
    
    protected $rules = [
        'id' => 'required',
        'password' => 'required|password|unique',
        'api_key' => 'requierd|api_key|unique',
        'statut' => 'required|in:libre,attribué'
    ];

    public function mount()
    {
        $this->updateCounts();
    }

    public function updateCounts()
    {
        $this->libres = InstanceQuota::where('statut', 'libre')->count();
        $this->attribues = InstanceQuota::where('statut', 'attribué')->count();
    }

    public function addInstance()
    {
        try{
            //Récupère les configurations de l'instance
            $this->getConfigDolibarr();

            $changeConfig = new ChangeConfigInstance(
                $this->dbName,
                $this->dbUser ,
                $this->dbPass,
                $this->prefix
            );

            //Génération de mot de passe
            $password = $this->generateComplexPassword();

            //Génération de l'api key
            $this->apiKey = $this->generateApiKey();

            //Crypter l'apiKey avec le cryptage de dolibarr
            $encryptApi=new EncryptApiService();
            $apiKeyCrypt = $encryptApi->dolEncryptApi($this->apiKey);

            //Hasher le mot de passe comme la méthode hashage de dolibarr 
            $passwordHash = $changeConfig->cryptDolibarrPassword($password);

            //Changement des enregistremets dans la base de donnée
            $changeConfigDatabase->ChangePassword($login, $passwordHash, $apiKeyCrypt);

            //Mise à jours de la base de donnée
            $instance = InstanceQuota::find($this->id);

            $instance->password = $this->password;
            $instance->api_key = $this->apiKey;
            $instance->statut = $this->statut;
            $instance->db_name = $this->dbName;
            $instance->db_user = $this->dbUser;
            $instance->db_pass = $this->dbPass;
            $instance->prefix = $this->prefix;
            $instance->instanceId = $this->instanceId;

            $instance->save();
    
            $this->reset(['id', 'password', 'api_key', 'statut']);
            $this->updateCounts();
            $this->dispatch('instanceAdded'); // Rafraîchit la liste
        } catch(\Exception $e){
            dd($e->getMessage());
        }   
    }

    public function getConfigDolibarr()
    {
        try{
             //Recherche l'url de l'instance pour rechercher les configurations de dolibarr
             $instance = InstanceQuota::find($this->id);
             $this->url = $instance->url;

            // Spécifie le chemin du fichier de configuration Dolibarr
            $folderName = parse_url($this->url, PHP_URL_HOST);
            $filePath = '/home/sc2sylg/Instance/'. $folderName . '/conf/conf.php';
            
            // Vérifie si le fichier existe
            if (!file_exists($filePath)) {
                dd("Fichier non trouvé !");
            }

            // Lit le contenu du fichier
            $configContent = file_get_contents($filePath);

            // Recherche les valeurs des variables avec des expressions régulières
            preg_match("/\\\$dolibarr_main_db_name\\s*=\\s*['\"](.*?)['\"];/", $configContent, $matchName);
            preg_match("/\\\$dolibarr_main_db_pass\\s*=\\s*['\"](.*?)['\"];/", $configContent, $matchPass);
            preg_match("/\\\$dolibarr_main_db_user\\s*=\\s*['\"](.*?)['\"];/", $configContent, $matchUser);
            preg_match("/\\\$dolibarr_main_db_prefix\\s*=\\s*['\"](.*?)['\"];/", $configContent, $matchPrefix);
            preg_match("/\\\$dolibarr_main_instance_unique_id\\s*=\\s*['\"](.*?)['\"];/", $configContent, $matchId);

            // Récupère les valeurs trouvées (ou une valeur par défaut si non trouvée)
            $this->dbName = $matchName[1] ?? null;
            $this->dbPass = $matchPass[1] ?? null;
            $this->dbUser = $matchUser[1] ?? null;
            $this->prefix = $matchPrefix[1] ?? null;
            $this->instanceId = $matchId[1] ?? null;

            // Vérifie si toutes les valeurs ont été trouvées
            if (!$this->dbPass || !$this->dbUser || !$this->instanceId || !$this->prefix) {
                dd("Une ou plusieurs valeurs manquent !");
            }


        } catch(\Exception $e){
            dd($e->getMessage());
        }
        
    }

    /**
     * Génère un mot de passe complexe aléatoire.
     *
     * @param int $length La longueur du mot de passe (par défaut : 10).
     *
     * @return string Le mot de passe généré.
     */
    public function generateComplexPassword(int $length = 10): string
    {
        // Définition des caractères autorisés
        $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $symbols = '!@#$%^&*()-_=+[]{}<>?/';

        // Assurer au moins une lettre, un chiffre, un symbole
        $password = [
            $letters[random_int(0, strlen($letters) - 1)],
            $numbers[random_int(0, strlen($numbers) - 1)],
            $symbols[random_int(0, strlen($symbols) - 1)],
        ];

        // Remplir le reste aléatoirement
        $all = $letters . $numbers . $symbols;
        for ($i = 3; $i <= $length; $i++) {
            $password[] = $all[random_int(0, strlen($all) - 1)];
        }

        // Mélanger le mot de passe final pour éviter un ordre prévisible
        shuffle($password);

        return implode('', $password);
    }

    /**
     * Génère un api key aléatoire.
     *
     * @param int $length La longueur de l'api key (32).
     *
     * @return string L'api key généré.
     */
    public function generateApiKey(int $length = 32): string
    {
        // Définition des caractères autorisés
        $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';

        // Assurer au moins une lettre, un chiffre, un symbole
        $apiKey = [
            $letters[random_int(0, strlen($letters) - 1)],
            $numbers[random_int(0, strlen($numbers) - 1)],
        ];

        // Remplir le reste aléatoirement
        $all = $letters . $numbers;
        for ($i = 3; $i <= $length; $i++) {
            $apiKey[] = $all[random_int(0, strlen($all) - 1)];
        }

        // Mélanger le mot de passe final pour éviter un ordre prévisible
        shuffle($apiKey);

        return implode('', $apiKey);
    }
    
    public function render()
    {
        return view('livewire.admin.instance-manager', [
            'instances' => InstanceQuota::all(),
        ]);
    }
}
