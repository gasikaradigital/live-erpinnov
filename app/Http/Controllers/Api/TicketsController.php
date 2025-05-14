<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\DolibarrApiService;

class TicketsController extends Controller
{
    /**
     * Récupère les tickets dans dolibarr via API
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTickets(){
        try {
             
            $dolibarrApiService = new DolibarrApiService("https://modelmg.erpinnov.com/api/index.php", "KzvPZvn2XXLK96C7t00c5Lp3gGu38sKw");
            
            $response = $dolibarrApiService->fetch("tickets/1");
         
            if (!$response->successful()) {
                return response()->json([
                    'message' => 'Failed to retrieve plans',
                ]);
            }

            return response()->json([
                'data' => [
                    'sujet' => $response->json('subject'),
                    'message' => $response->json('message')
                ],
                'status' => $response->statut(),
            ]);
           
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ]);     
        }
    }

}
