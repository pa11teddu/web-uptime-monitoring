<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Retrieve all registered clients
     */
    public function getAllClients(): JsonResponse
    {
        $clients = Client::select('id', 'email')
            ->orderBy('email')
            ->get();

        return response()->json($clients);
    }

    /**
     * Alias for backward compatibility
     */
    public function index()
    {
        return $this->getAllClients();
    }

    /**
     * Get all monitored websites for a specific client
     */
    public function getClientWebsites(Client $client): JsonResponse
    {
        $websites = $client->monitoredSites()
            ->with('latestLog')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($websites);
    }

    /**
     * Alias for backward compatibility
     */
    public function websites(Client $client)
    {
        return $this->getClientWebsites($client);
    }

    /**
     * Find a client by their email address
     */
    public function findClientByEmail(Request $request): JsonResponse
    {
        $input = $request->validate([
            'email' => 'required|email|max:255'
        ]);

        $foundClient = Client::where('email', $input['email'])->first();

        if ($foundClient === null) {
            return response()->json([
                'error' => 'Client not found',
                'message' => 'No client exists with the provided email address'
            ], 404);
        }

        return response()->json($foundClient);
    }

    /**
     * Alias for backward compatibility
     */
    public function lookup(Request $request)
    {
        return $this->findClientByEmail($request);
    }
}
