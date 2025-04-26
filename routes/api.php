<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SensorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Api\DeviceController;
use App\Models\SensorData;


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

Route::post('/devices', [DeviceController::class, 'store']);

Route::post('/sensor', [SensorController::class, 'store']);

// routes/api.php

Route::post('/gas-alert', [DashboardController::class, 'store']);

Route::get('/sensor', [SensorController::class, 'index']);

Route::get('/devices', [DeviceController::class, 'index']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
