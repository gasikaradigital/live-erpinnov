<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TicketsController extends Controller
{
    public function getTickets(){
        try {
            $response = Http::withHeaders([
                'DOLAPIKEY' => 'KzvPZvn2XXLK96C7t00c5Lp3gGu38sKw',
            ])->get('https://modelmg.erpinnov.com/api/index.php/tickets/1', [
                'limit' => 100,
                'sortfield' => 'ref',
                'sortorder' => 'ASC',
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'Erreur API: ' => $response->status(),
                ]);
            }

            return response()->json([
                'data' => [
                    'sujet' => $response->json('subject'),
                    'message' => $response->json('message')
                ],
                'status' => $response->status(),
            ]);

            

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ]);

            
        }
    }
}
