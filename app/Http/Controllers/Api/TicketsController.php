<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tickets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\DolibarrApiService;
use Exception;
use Google\Service\CloudSourceRepositories\Repo;
use PHPUnit\Framework\Attributes\Ticket;

class TicketsController extends Controller
{
    /**
     * Récupère les tickets dans dolibarr via API
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private $dolibarrApiService;

    public function __construct(DolibarrApiService $dolibarr_api)
    {
        $this->dolibarrApiService = $dolibarr_api;
    }
    public function getTickets(Request $request)
    {
        $user = $request->user();

        $tickets = $user->tickets()->get();

        return $tickets;
    }

    public function create(Request $request)
    {
        $user = $request->user();

        try {
            $user->tickets()->create([
                'sujet' => $request->sujet,
                'message' => $request->message
            ]);

            return response()->json([
                'Message' => 'Ticket créée avec succès',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'Erreur' => $e->getMessage(),
            ]);
        }
    }

    private function fetchFromDolibarr()
    {
        try {

            $response = $this->dolibarrApiService->fetch("tickets");

            return response()->json($response, 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ]);
        }
    }
}
