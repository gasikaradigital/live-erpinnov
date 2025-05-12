<?php

namespace App\Mappers;

use App\DTO\EnterpriseDto;
use App\Models\Entreprise;

class EnterpriseMapper
{

    /**
     * Mapping reponse dolibarr vers EntrepriseDto
     * 
     */
    public static function mapFromApiData(array $apiData): EnterpriseDto
    {
        $dto = new EnterpriseDto();

        $dto->id = $apiData['id'];
        $dto->name = $apiData['name'] ?? '';
        $dto->ville = $apiData['town'] ?? '';
        $dto->adresse = $apiData['address'] ?? '';
        $dto->pays = $apiData['country_code'] ?? '';
        $dto->phone = $apiData['phone'] ?? '';
        $dto->clientCode = $apiData['code_client'] ?? '';

        $arrayOptions = $apiData['array_options'] ?? [];

        $dto->ownerId = $arrayOptions['options_owner_id'] ?? 0;
        $dto->nif = $arrayOptions['options_nif'] ?? '';
        $dto->employeCount = $arrayOptions['options_employee_count'] ?? 0;

        if (isset($arrayOptions['options_type'])) {
            $dto->type = $arrayOptions['options_type'];
        }

        return $dto;
    }

    public static function mapFromModel(Entreprise $model): EnterpriseDto
    {
        return new EnterpriseDto(
            id: $model->id,
            ownerId: $model->user_id,
            name: $model->name,
            nif: $model->nif,
            employeCount: $model->employees_count ?? 0,
            ville: $model->ville,
            pays: $model->pays,
            phone: $model->phone,
            adresse: $model->adresse,
        );
    }

    public static function mapToModel(EnterpriseDto $dto): Entreprise
    {
        return new Entreprise([
            'user_id' => $dto->ownerId,
            'name' => $dto->name,
            'nif' => $dto->nif,
            'employe_count' => $dto->employeCount,
            'ville' => $dto->ville,
            'pays' => $dto->pays,
            'phone' => $dto->phone,
            'adresse' => $dto->adresse,
        ]);
    }

    public static function mapToApiData(EnterpriseDto $dto): array
    {
        $country_id = "";
        switch (strtolower($dto->pays)) {
            case 'fr':
            case 'france':    
                $country_id = "1";
                break;
            case 'be':
            case 'belgique':    
                $country_id = "2";
                break;
            case 'ch':
            case 'suisse' :   
                $country_id = "6";
                break;
            case 'mg':
            case 'madagascar':    
                $country_id = "143";
                break;
            default:
                $country_id = "143";
                break;
        }
        return [
            "name" => $dto->name,
            "client" => 1,
            "town" => $dto->ville,
            "address" => $dto->adresse,
            "country_id" => $country_id,
            "phone" => $dto->phone,
            "array_options" => [
                "options_owner_id" => $dto->ownerId,
                "options_nif" => $dto->nif,
                "options_type" => "ERP_ENTERPRISE",
                "options_employee_count" => $dto->employeCount
            ]
        ];
    }
}
