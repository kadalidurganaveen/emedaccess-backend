<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{
    public function index()
    {
        $specialties = Specialty::all()->map(function ($spec) {
            return [
                'id' => $spec->id,
                'name' => $spec->name,
                'description' => $spec->description, // ✅ Make sure this is included
                'image' => url('uploads/specialty/' . $spec->image)
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $specialties
        ]);
    }

    public function show($id)
    {
        $spec = Specialty::find($id);

        if (!$spec) {
            return response()->json(['status' => 'error', 'message' => 'Specialty not found'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $spec->id,
                'name' => $spec->name,
                'description' => $spec->description,
                'image' => url('uploads/specialty/' . $spec->image)
            ]
        ]);
    }
}
