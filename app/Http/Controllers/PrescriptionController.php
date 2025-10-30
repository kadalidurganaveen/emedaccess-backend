<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prescription;

class PrescriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = Prescription::query();

        if ($request->has('name') && !empty($request->name)) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->has('pharmacy') && !empty($request->pharmacy)) {
            $query->where('pharmacy', $request->pharmacy);
        }

        if ($request->has('type') && !empty($request->type)) {
            $query->where('type', $request->type);
        }

        $results = $query->get();

        if ($results->isEmpty()) {
            return response()->json(['error' => 'No matching prescriptions found.'], 404);
        }

        return response()->json($results);
    }
}
