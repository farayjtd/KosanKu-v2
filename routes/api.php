<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Default API route untuk testing
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route test untuk debugging
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!', 'timestamp' => now()]);
});

Route::post('/test', function (Request $request) {
    return response()->json([
        'message' => 'POST API is working!',
        'data' => $request->all(),
        'timestamp' => now()
    ]);
});

// Route callback Tripay
Route::post('/tripay/callback', [PaymentController::class, 'handleTripayCallback'])
    ->name('tripay.callback');

// Route callback alternatif untuk testing
Route::any('/tripay/test', function (Request $request) {
    return response()->json([
        'message' => 'Tripay test endpoint works!',
        'method' => $request->method(),
        'data' => $request->all(),
        'headers' => $request->headers->all()
    ]);
});

// Route debug untuk melihat konfigurasi
Route::get('/debug/config', function () {
    return response()->json([
        'tripay_config' => [
            'merchant_code' => config('services.tripay.merchant_code') ? 'SET' : 'NOT SET',
            'api_key' => config('services.tripay.api_key') ? 'SET' : 'NOT SET',
            'private_key' => config('services.tripay.private_key') ? 'SET' : 'NOT SET',
            'is_production' => config('services.tripay.is_production', false),
            'callback_url' => config('services.tripay.callback_url', 'NOT SET'),
        ]
    ]);
});