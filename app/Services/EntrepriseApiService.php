<?php

namespace App\Services;

use App\DTO\EnterpriseDto;
use App\Mappers\EnterpriseMapper;

class EntrepriseApiService
{

    private DolibarrApiService $dolibarrApiService;
    private ClientCodeGeneratorService $clientCodeGeneratorService;
    public function __construct(DolibarrApiService $dolibarrApiService, ClientCodeGeneratorService $clientCodeGeneratorService)
    {
        $this->dolibarrApiService = $dolibarrApiService;
        $this->clientCodeGeneratorService = $clientCodeGeneratorService;
    }

    /**
     * recuperation des entreprises
     * @return array<EnterpriseDto>
     */
    public function fetchEntreprises(): array
    {
        $query = ["sqlfilters" => "(ef.type:like:'ERP_ENTERPRISE')"];

        return $this->dolibarrApiService->fetch(
            endpoint: "thirdparties",
            dtoClass: EnterpriseDto::class,
            query: $query,
            mapper: fn(array $apiData): EnterpriseDto => EnterpriseMapper::mapFromApiData($apiData)
        );
    }

    /**
     * recuperation par ownerId
     * @param int $ownerId user lié à l'entreprise
     * @return array<EnterpriseDto>
     */
    public function fetchEntreprisesByOwner(int $ownerId): array
    {

        $query = ["sqlfilters" => "(ef.type:like:'ERP_ENTERPRISE')and(ef.owner_id:like:'" . $ownerId . "')"];

        return $this->dolibarrApiService->fetch(
            endpoint: "thirdparties",
            query: $query,
            dtoClass: EnterpriseDto::class,
            mapper: fn(array $apiData): EnterpriseDto => EnterpriseMapper::mapFromApiData($apiData)
        );
    }

    /**
     * recuperation par nif
     * @param string $nif Nif de l'entreprise
     * @return array<EnterpriseDto>
     */
    public function fetchEntrepriseByNif(string $nif): array
    {
        $query = ["sqlfilters" => "(ef.type:like:'ERP_ENTERPRISE')and(ef.nif:like:'" . $nif . "')"];

        return $this->dolibarrApiService->fetch(
            endpoint: "thirdparties",
            query: $query,
            dtoClass: EnterpriseDto::class,
            mapper: fn(array $apiData): EnterpriseDto => EnterpriseMapper::mapFromApiData($apiData)
        );
    }

    /**
     * Creation entreprise dans dolibarr
     * @param EnterpriseDto $enterprise Dto entreprise à créer
     * @return int Id de l'entreprise dans dolibarr
     */
    public function pushEntreprise(EnterpriseDto $enterprise): int
    {
        $apiData = EnterpriseMapper::mapToApiData($enterprise);

        return $this->dolibarrApiService->create(
            endpoint:"thirdparties",
            data:$apiData,
            preprocessor:function ($data) {
                $data['code_client'] = $this->clientCodeGeneratorService->generate();
                return $data;
            }
        );
    }
}
