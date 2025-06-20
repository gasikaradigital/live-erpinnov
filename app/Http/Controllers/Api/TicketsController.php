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

use function Pest\Laravel\json;

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
        if ($request->status != null && $request->status != 'EN_COURS' && $request->status != 'RESOLU') {
            return response()->json(["Message" => "status EN_COURS ou RESOLU"], 400);
        }
        try {
            $user->tickets()->create([
                'sujet' => $request->sujet ?? '',
                'message' => $request->message ?? '',
                'status' => $request->status ?? 'EN_COURS'
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

    public function update(Request $request, $id)
    {
        $user = $request->user();

        $ticket = $user->tickets()->find($id);

        if (!$ticket) {
            return response()->json(['Message' => 'Ticket non trouvé'], 404);
        }

        if ($request->status !== null && !in_array($request->status, ['EN_COURS', 'RESOLU'])) {
            return response()->json(["Message" => "status doit être EN_COURS ou RESOLU"], 400);
        }

        try {
            $ticket->update([
                'sujet' => $request->sujet ?? $ticket->sujet,
                'message' => $request->message ?? $ticket->message,
                'status' => $request->status ?? $ticket->status,
            ]);

            return response()->json(['Message' => 'Ticket mis à jour avec succès'], 200);
        } catch (\Exception $e) {
            return response()->json(['Erreur' => $e->getMessage()], 500);
        }
    }

    public function delete(Request $request, $id)
    {
        $user = $request->user();

        $ticket = $user->tickets()->find($id);

        if (!$ticket) {
            return response()->json(['Message' => 'Ticket non trouvé'], 404);
        }

        try {
            $ticket->delete();

            return response()->json(['Message' => 'Ticket supprimé avec succès'], 200);
        } catch (\Exception $e) {
            return response()->json(['Erreur' => $e->getMessage()], 500);
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
