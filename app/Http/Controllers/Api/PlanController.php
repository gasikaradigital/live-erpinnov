<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\SubPlan;
use Illuminate\Support\Facades\Http;
use App\Services\DolibarrApiService;

class PlanController extends Controller
{
        /**
     * Returns a JSON response containing all plans and sub-plans.
     *
     * @return \Illuminate\Http\JsonResponse
     */

      // Constantes
    const TYPE_PRODUIT = 0;
    const TYPE_SERVICE = 1;

    const STATUS_HORS_VENTE = 0;
    const STATUS_EN_VENTE = 1;

    const STATUS_HORS_ACHAT = 0;
    const STATUS_EN_ACHAT = 1;

    public $data;

    /**
     * Récupère les plans dans Dolibarr via API
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function plan()
    {
        $user = auth()->user();
        try {
            $dolibarrApiService = new DolibarrApiService("https://modelmg.erpinnov.com/api/index.php", "KzvPZvn2XXLK96C7t00c5Lp3gGu38sKw");

            $response = $dolibarrApiService->fetch("products");

            if (!$response->successful()) {
                return response()->json([
                    'message' => 'Failed to retrieve plans',
                ]);
            }

            $data = collect($response->json())->map(function ($item) {
                return (object) [
                    'id' => $item['id'],
                    'ref' => $item['ref'],
                    'label' => $item['label'],
                    'description' => $item['description'] ?? '',
                    'type' => $item['type'],
                    'type_label' => $item['type'] == self::TYPE_PRODUIT ? 'Produit' : 'Service',
                    'price' => number_format($item['price'], 2, ',', ' ') . ' €',
                    'price_min' => number_format($item['price_min'], 2, ',', ' ') . ' €',
                    'price_ttc_formatted' => number_format($item['price_ttc'], 2, ',', ' ') . ' €',
                    'tva_tx' => $item['tva_tx'],
                    'status' => $item['status'],
               
                ];
            })->all();

            
                //Récupère seulement les produits
            foreach($data as $produit)
            {
                if($produit->type == 0){
                    $this->data[] = $produit;
                }
            }

            return response()->json([
                'plan' => $this->data,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'erreur' => $e->getMessage(),
            ]);
        }
    }
}
