<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vessel;

class VesselController extends Controller
{
    /**
     * List all vessels for the authenticated organization
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Return vessels where agent_id matches user's organization
        $vessels = Vessel::where('agent_id', $user->organization_id)->get();

        return response()->json([
            'data' => $vessels
        ]);
    }

    /**
     * Register a new vessel
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'vessel_type' => 'required|string',
            'imo_number' => 'required|string|unique:vessels,imo_number',
            'loa_meters' => 'required|numeric',
            'draft_meters' => 'required|numeric',
        ]);

        $user = $request->user();

        $vessel = Vessel::create([
            'name' => $request->name,
            'vessel_type' => $request->vessel_type,
            'imo_number' => $request->imo_number,
            'loa_meters' => $request->loa_meters,
            'draft_meters' => $request->draft_meters,
            'status' => 'active',
            'agent_id' => $user->organization_id, // Link to user's org
        ]);

        return response()->json([
            'message' => 'Vessel registered successfully',
            'data' => $vessel
        ], 201);
    }

    /**
     * Get single vessel details
     */
    public function show(Request $request, Vessel $vessel)
    {
        // Ensure user owns this vessel
        if ($vessel->agent_id !== $request->user()->organization_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json(['data' => $vessel]);
    }
}
