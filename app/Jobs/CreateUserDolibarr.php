<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Entreprise;
use Carbon\Carbon;


class CreateUserDolibarr implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $config, $name, $entreprise, $value;
 
    /**
     * Create a new job instance.
     */
    public function __construct($name, $entreprise)
    {

        $this->entreprise = $entreprise;
        $this->name = $name;
  
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $this->setValue();
        
        try {
            $apiData = [
                ...$this->value,
                'statut' => 1,
                'entity' => 1
            ];

            Log::info('Données envoyées à l\'API:', $apiData);
            //sZiYMfRJ5JDi
            $response = Http::withHeaders([
                'DOLAPIKEY' => '3at1TxcD44CYN4J9LJ23ldG6r7VrcdTu',
                'Accept' => 'application/json'
            ])->post( 'https://gmg.erpinnov.com' . '/api/index.php/users', $apiData);

            if (!$response->successful()) {
                Log::error('Réponse API Dolibarr: ' . $response->body());
                throw new Exception('Erreur API: ' . $response->body());
            }

            Log::info('contact créer avec succès');
        } catch (Exception $e) {
            Log::error('Erreur création de contact: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
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
