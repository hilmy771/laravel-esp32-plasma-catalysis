<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WhatsAppService;
use App\Models\SensorData;
use App\Models\User;
use App\Models\Device;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $rooms = Device::distinct()->pluck('room_name');

        $selectedRoom = $request->input('room_name');
        $selectedDevice = $request->input('device_id');

        $devices = collect(); // default kosong

        if ($selectedRoom) {
            $devices = Device::where('room_name', $selectedRoom)->get();
        }

        // Ambil data sensor jika perangkat dipilih
        $sensorData = collect();

        if ($selectedDevice) {
            $sensorData = SensorData::where('device_id', $selectedDevice)->get();
        }

        return view('dashboard', compact('rooms', 'devices', 'sensorData', 'selectedRoom', 'selectedDevice'));
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
