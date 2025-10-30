<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CostTransparency;

class CostTransparencyController extends Controller
{
    public function index(Request $request)
    {
        $service = $request->query('service');

        if (!$service) {
            return response()->json(['error' => 'Service name is required'], 400);
        }

        $result = CostTransparency::where('service_name', 'LIKE', "%$service%")->first();

        if (!$result) {
            return response()->json(['error' => 'No cost data found for this service'], 404);
        }

        return response()->json([
            'service' => $result->service_name,
            'min_price' => $result->min_price,
            'max_price' => $result->max_price
        ]);
    }
}
