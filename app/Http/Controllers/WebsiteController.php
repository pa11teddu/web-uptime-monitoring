<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class WebsiteController extends Controller
{
    /**
     * Register a new website for monitoring
     */
    public function register(Request $request): Website
    {
        $input = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'url' => 'required|url|max:2048',
        ]);

        $newWebsite = Website::create([
            'client_id' => $input['client_id'],
            'url' => $input['url'],
            'is_active' => true,
        ]);

        return $newWebsite;
    }

    /**
     * Alias for backward compatibility
     */
    public function store(Request $request): Website
    {
        return $this->register($request);
    }

    /**
     * Retrieve monitoring statistics for the past 24 hours
     */
    public function getStatistics(Website $website): JsonResponse
    {
        $oneDayAgo = Carbon::now()->subDay();
        
        $statistics = $website->monitoringRecords()
            ->where('created_at', '>=', $oneDayAgo)
            ->orderBy('created_at', 'asc')
            ->get(['status_code', 'response_time', 'created_at']);

        return response()->json($statistics);
    }

    /**
     * Alias for backward compatibility
     */
    public function stats(Website $website)
    {
        return $this->getStatistics($website);
    }

    /**
     * Remove a website from monitoring
     */
    public function remove(Website $website): JsonResponse
    {
        $website->delete();
        return response()->json(null, 204);
    }

    /**
     * Alias for backward compatibility
     */
    public function destroy(Website $website)
    {
        return $this->remove($website);
    }
}
