<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\SubPlan;
use Illuminate\Support\Facades\Http;

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

    public function plan()
    {
        /*$this->data = $this->getFromDatabase();
        return response()->json([
            'plan' => $this->data,
        ], 200);*/
        return response()->json(['arrive' => "ok"], 200);
    }

    private function fetchFromDolibarr()
    {
        $user = auth()->user();
        try {

            $response = Http::withHeaders([
                'DOLAPIKEY' => 'V8ARU7g614rfiu5Dft2fbj4P6xXDO9TN',
            ])->get('https://gmg.erpinnov.com' . '/api/index.php/products', [
                'limit' => 100,
                'sortfield' => 'ref',
                'sortorder' => 'ASC',
            ]);

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
            foreach ($data as $produit) {
                if ($produit->type == 0) {
                    $this->data[] = $produit;
                }
            }

            return $data;
        } catch (Exception $e) {
            return response()->json([
                'erreur' => $e->getMessage(),
            ]);
        }
    }

    private function getFromDatabase()
    {
        $plans = Plan::all();
        $data = [];
        foreach ($plans as $plan) {
             array_push($data,$this->mapFromModel($plan));
        }
        return $data;
    }

    private function mapFromModel(Plan $plan)
    {

        $price_min = $plan->subPlans[0]->price_local;

        $sub_plans = [];
        foreach ($plan->subPlans as $subplan) {

            if ($price_min > $subplan->price_local) {
                $price_min = $subplan->price_local;
            }
            array_push($sub_plans, [
                'id'=>$subplan->id,
                'label'=>$subplan->name,
                'features'=>$subplan->features,
                'price_monthly'=>$subplan->price_monthly,
                'price_yearly'=>$subplan->price_yearly,
                'price_monthly_formated'=>$subplan->price_local
            ]);
        }
        return [
            'id' => $plan->id,
            'label' => $plan->name,
            'description' => $plan->description,
            'features' => $plan->features,
            'price_min' => $price_min,
            'sub_plans' => $sub_plans
        ];
    }
}