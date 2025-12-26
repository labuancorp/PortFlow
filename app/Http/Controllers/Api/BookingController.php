<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PortCall;
use App\Models\Vessel;
use Illuminate\Support\Str;
use App\Services\BerthOptimizationService;

class BookingController extends Controller
{
    /**
     * List all bookings for authenticated organization
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $bookings = PortCall::where('agent_id', $user->organization_id)
            ->with(['vessel', 'berth'])
            ->orderBy('eta', 'desc')
            ->paginate(20);

        return response()->json($bookings);
    }

    /**
     * Create a new berth request
     */
    public function store(Request $request)
    {
        $request->validate([
            'vessel_id' => 'required|exists:vessels,id',
            'eta' => 'required|date|after:now',
            'etd' => 'required|date|after:eta',
            'auto_assign' => 'boolean' // Optional flag to auto-accept AI suggestion
        ]);

        $user = $request->user();
        $vessel = Vessel::findOrFail($request->vessel_id);

        // Authorization check
        if ($vessel->agent_id !== $user->organization_id) {
            return response()->json(['message' => 'Unauthorized vessel'], 403);
        }

        $berthId = null;

        // Use AI optimization if auto_assign is requested
        if ($request->boolean('auto_assign')) {
            $optimizer = new BerthOptimizationService();
            $suggestions = $optimizer->findOptimalBerths($vessel, $request->eta, $request->etd);
            
            if (!empty($suggestions) && $suggestions[0]['available']) {
                $berthId = $suggestions[0]['berth']->id;
            }
        }

        $booking = PortCall::create([
            'vessel_id' => $vessel->id,
            'agent_id' => $user->organization_id,
            'assigned_berth_id' => $berthId,
            'status' => 'requested',
            'eta' => $request->eta,
            'etd' => $request->etd,
            'reference_no' => 'REQ-' . strtoupper(Str::random(6)),
        ]);

        return response()->json([
            'message' => 'Booking created successfully',
            'data' => $booking->load('berth')
        ], 201);
    }

    /**
     * Get booking details
     */
    public function show(Request $request, PortCall $portCall)
    {
        if ($portCall->agent_id !== $request->user()->organization_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json(['data' => $portCall->load(['vessel', 'berth', 'invoice'])]);
    }
}
