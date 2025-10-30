<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    public function index()
    {
        $members = TeamMember::all()->map(function($mem) {
            return [
                'id' => $mem->id,
                'name' => $mem->name,
                'role' => $mem->role,
                'bio' => $mem->bio,
                // Use 'photo' field from model
                'photo' => $mem->photo ? url('uploads/team/' . $mem->photo) : url('uploads/team/default.jpg'),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $members
        ]);
    }
}
