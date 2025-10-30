<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\CostTransparencyController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\API\TeamMemberController;
use App\Http\Controllers\API\SpecialtyController;

Route::get('/health', function () {
    return response()->json(['status' => 'healthy', 'timestamp' => now()]);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/clinics', [ClinicController::class, 'index']);
Route::get('/cost', [CostTransparencyController::class, 'index']);
Route::get('/prescriptions', [PrescriptionController::class, 'index']);
Route::get('/blogs', [BlogController::class, 'index']);
Route::get('/blogs/{id}', [BlogController::class, 'show']);
Route::get('/team', [TeamMemberController::class, 'index']);
Route::get('/specialties', [SpecialtyController::class, 'index']);