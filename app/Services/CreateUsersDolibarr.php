<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\OtherTable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Carbon\Carbon;

class CreateUsersDolibarr
{
    public $name, $entreprise, $value, $dolibarrApiKey, $urlDolibarr;

    /**
     * Create a new  user Dolibarr.
     */
    public function __construct($name, $entreprise, $instance_free)
    {

        $this->entreprise = $entreprise;
        $this->name = $name;
        $this->dolibarrApiKey = $instance_free->dolibarrApiKey;
        $this->urlDolibarr = $instance_free->url;
    }

    public function create(){
        $this->setValue();

        try{
            $apiData = [
                ...$this->value,
                'statut' => 1,
                'entity' => 1
            ];

            Log::info('Données envoyées à l\'API:', $apiData);
            //sZiYMfRJ5JDi
            $response = Http::withHeaders([
                'DOLAPIKEY' => $this->dolibarrApiKey,
                'Accept' => 'application/json'
            ])->post( $this->urlDolibarr . '/api/index.php/users', $apiData);

            if (!$response->successful()) {
                Log::error('Réponse API Dolibarr: ' . $response->body());
                return false;
            }

            Log::info('contact créer avec succès');
            return true;
        } catch (\Exception $e){
            logger('Erreur dans la création d\'utilisateur', $e->getMessage());
            return null;
        }
        
    }

    public function setValue(){
        $this->value = [
            "admin" => "0",
            "country_code" => $this->entreprise->pays === "Madagascar" ? "MG" : null,
            "country_id" => $this->entreprise->pays === "Madagascar" ? "143" : null,
            "town" => $this->entreprise->ville,
            "datestarvalidity" => Carbon::now(),
            "dateendvalidity" => Carbon::now()->addDays(15),
            "employee" => "1",
            "lastname" => $this->name,
            "login" => $this->name,
            "password" => "passwordtest",
             
        ];
    }

}
