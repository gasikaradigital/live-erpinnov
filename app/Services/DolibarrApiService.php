<?php

namespace App\Services;

use App\DTO\EnterpriseDto;
use Closure;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DolibarrApiService
{
    private $baseUrl;
    private $apiKey;


    public function __construct(string $baseUrl, string $apiKey)
    {
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
    }

    /**
     * Exécute une requête GET vers l'API Dolibarr
     *
     * @param string $endpoint Endpoint API (ex: "thirdparties", "invoices")
     * @param array $query Paramètres de requête (facultatif)
     * @param string|null $dtoClass Classe DTO pour le mapping (facultatif)
     * @param Closure|null|callable $mapper Fonction de mapping personnalisée (requis si $dtoClass est fourni)
     * 
     * @return array Données brutes ou tableau d'objets DTO mappés
     * 
     * @throws Exception Si la requête échoue ou si le mapping est invalide
     */

     public function fetch(
        string $endpoint,
        array $query = [],
        ?string $dtoClass = null,
        null|Closure|callable $mapper = null
    ) {
        if ($dtoClass && !$mapper) {
            throw new \InvalidArgumentException("Un mapper est requis quand un DTO est spécifié");
        }
    
        Log::info("dolibar url ",["{$this->baseUrl}/{$endpoint}"]);
        // Récupération des données
        $response = Http::withHeaders([
            'DOLAPIKEY' => $this->apiKey,
            'Accept' => 'application/json',
        ])->get("{$this->baseUrl}/{$endpoint}/2");
    
        if ($response->failed()) {
            throw new \RuntimeException("Erreur API Dolibarr: " . $response->body());
        }
    
        $apiData = $response->json();
    
        // Si pas de DTO, on retourne les données brutes
        if (!$dtoClass || !$mapper) {
            return $apiData;
        }
    
        // Application du mapper
        if (array_is_list($apiData)) {
            return array_map(
                fn(array $item) => $mapper($item),
                $apiData
            );
        }
    
        return [$mapper($apiData)];
    }    

    /**
     * methode de création par defaut
     * 
     * @param string $endpoint Le endpoint API (ex: "thirdparties")
     * @param array $data Les données à envoyer (seront converties en JSON)
     * @param Closure|null $preprocessor Preprocesseur optionnel pour enrichire le body
     * @return array|mixed
     * @throws Exception
     */

    public function create(
        string $endpoint,
        array $data,
        ?Closure $preprocessor = null
    ) {
        if ($preprocessor) {
            $data = $preprocessor($data); // Permet d'enrichir les données
        }

        $response = Http::withHeaders([
            'DOLAPIKEY' => $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post("{$this->baseUrl}/{$endpoint}", $data);

        if ($response->failed()) {
            throw new Exception("Dolibarr POST failed - Status: {$response->status()}, Error: {$response->body()}");
        }

        $responseData = $response->json();

        return $responseData;
    }

    /**
     * methode de modification par defaut
     * 
     * @param string $endpoint Le endpoint API (ex: "thirdparties")
     * @param array $data Les données à envoyer (seront converties en JSON)
     * @param Closure|null $preprocessor Preprocesseur optionnel pour enrichire le body
     * @return array|mixed
     * @throws Exception
     */

    public function modify(
        string $endpoint,
        array $data
    ): array {

        $response = Http::withHeaders([
            'DOLAPIKEY' => $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->put("{$this->baseUrl}/{$endpoint}", $data);

        if ($response->failed()) {
            throw new Exception("Dolibarr Put failed - Status: {$response->status()}, Error: {$response->body()}");
        }

        $responseData = $response->json();

        return $responseData;
    }
}
