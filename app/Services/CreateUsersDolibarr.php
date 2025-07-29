<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\OtherTable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;


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
        $this->dolibarrApiKey = $instance_free->api_key;
        $this->urlDolibarr = $instance_free->url;
    }

    public function createUser(){
        $this->setValue();

        try{
            $apiData = [
                ...$this->value,
                'statut' => 1,
                'entity' => 1
            ];

            Log::info('Données envoyées à l\'API:', $apiData);
            
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
             
        ];
       
    }

    public function setPassword($instance_free, $passwordHash){
        try {

            config(['database.connections.dynamic' => [
                'driver' => 'mariadb',
                'host' => 'localhost',
                'database' => $instance_free->db_name,
                'username' => $instance_free->db_user,
                'password' => $instance_free->db_pass,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => false,
                'engine' => null,
            ]]);
    
            DB::purge('dynamic');
            DB::reconnect('dynamic');
           
            DB::connection('dynamic')->table($instance_free->prefix.'user')
                ->where('rowid', 11)
                ->update([
                    'pass_crypted' => $passwordHash
                ]);
    
            return true;
        } catch (\Exception $e) {
            \Log::error("Erreur lors de la mise à jour : " . $e->getMessage());
            return false;
        }
    }

}
