<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mappers\EnterpriseMapper;
use App\Models\Entreprise;
use App\Services\EntrepriseApiService;
use Illuminate\Http\Request;

class TestDolibarrApi extends Controller
{
    protected EntrepriseApiService $entrepriseApiService;
    public function __construct(EntrepriseApiService $entrepriseApiService)
    {
        $this->entrepriseApiService = $entrepriseApiService;
    }

    public function getEntreprises(Request $request){
        return response()->json($this->entrepriseApiService->fetchEntreprises(),200);
    }

    public function testCreateEntreprise(Request $request){
        $entreprise = Entreprise::find(3);
        $apiData = EnterpriseMapper::mapFromModel($entreprise);

        return response()->json($this->entrepriseApiService->pushEntreprise($apiData),200);
    }
}
