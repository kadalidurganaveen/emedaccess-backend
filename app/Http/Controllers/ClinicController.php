<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clinic;

class ClinicController extends Controller
{
    public function index(Request $request)
    {
        $zip = $request->query('zip');
        $specialty = $request->query('specialty');
        $clinicType = $request->query('clinic_type');
        $sort = $request->query('sort');

        $query = Clinic::query();

        if ($zip) $query->where('zip_code', $zip);
        if ($specialty) $query->where('specialty', $specialty);
        if ($clinicType) $query->where('clinic_type', $clinicType);

        if ($sort === 'distance') $query->orderBy('distance', 'asc');
        if ($sort === 'rating') $query->orderBy('rating', 'desc');

        $clinics = $query->get();

        return response()->json($clinics);
    }
}
