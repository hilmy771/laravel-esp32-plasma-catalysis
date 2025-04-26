<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WhatsAppService;
use App\Models\SensorData;
use App\Models\User;
use App\Models\Device;


class DashboardController extends Controller
{
    public function index()
    {
        $devices = Device::all();
        $sensorData = SensorData::latest()->take(10)->get(); // Ambil 10 data terbaru

        return view('dashboard', compact('devices', 'sensorData'));
    }

    public function checkGasLevel()
{
    $latestSensorData = SensorData::latest()->first();
    if (!$latestSensorData) {
        return response()->json(['error' => 'Tidak ada data sensor']);
    }

    $gasLevel = $latestSensorData->gas_value;
    $gasThreshold = 100; // Batas gas berbahaya

    if ($gasLevel >= $gasThreshold) {
        $whatsappService = new WhatsAppService();
        $whatsappService->sendGasAlert($gasLevel);
    }

    return response()->json(['gasLevel' => $gasLevel]);
}
}
