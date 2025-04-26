<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SensorData;
use App\Models\Device;
use App\Events\GasThresholdExceeded;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    // Mendapatkan data terbaru dari device tertentu untuk Dashboard
    public function index(Request $request)
    {
        $request->validate([
            'device_id' => 'required|exists:devices,id'
        ]);

        $sensorData = SensorData::where('device_id', $request->device_id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

            return response()->json($sensorData->map(function ($data) {
                return [
                    // 'mq4_value' => $data->mq4_value,
                    'mq6_value' => $data->mq6_value,
                    'mq8_value' => $data->mq8_value,
                    'created_at' => $data->created_at->toISOString(), // Format ISO 8601 (standar JavaScript)
                ];
            }));
    }

    // Menampilkan semua data sensor untuk halaman Data Sensor
    public function getAllSensorData(Request $request)
    {
        $deviceId = $request->input('device_id');
        $date = $request->input('date');

        // Query sensor data dengan filter perangkat dan tanggal
        $query = SensorData::with('device');

        if ($deviceId) {
            $query->where('device_id', $deviceId);
        }

        if ($date) {
            $query->whereDate('created_at', $date);
        }

        // Menggunakan pagination dengan 10 data per halaman
        $sensorData = $query->orderBy('created_at', 'desc')->paginate(10);
        $devices = Device::all();

        return view('sensor_data', compact('sensorData', 'devices'));
    }

    // Menyimpan data sensor ke dalam database
    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required|string|exists:devices,token',
            // 'gas_value_mq4' => 'required|numeric',
            'gas_value_mq6' => 'required|numeric',
            'gas_value_mq8' => 'required|numeric',
        ]);

        $device = Device::where('token', $request->token)->first();

        if (!$device) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        $sensorData = SensorData::create([
            'device_id' => $device->id,
            // 'mq4_value' => $request->gas_value_mq4,
            'mq6_value' => $request->gas_value_mq6,
            'mq8_value' => $request->gas_value_mq8,
            'created_at' => now(), // Tambahkan ini agar Laravel tidak menggunakan default '1970-01-01'
        ]);

        $thresholds = [
            // 'mq4_value' => 300,
            'mq6_value' => 300,
            'mq8_value' => 300
        ];

        $sensorNames = [
            'mq6_value' => 'Propane/Butane Gas',
            'mq8_value' => 'Hydrogen Gas'
        ];

        $alerts = [];
        foreach ($thresholds as $sensor => $threshold) {
            $sensorValue = $sensorData->$sensor; // Gunakan $sensorData yang baru dibuat
            if ($sensorValue >= $threshold) {
                $sensorName = $sensorNames[$sensor] ?? $sensor;
                $alerts[] = "⚠️ *Peringatan!* Sensor *{$sensorName}* mendeteksi gas berbahaya! \n🔥 Level: *{$sensorValue}* ppm 🚨";
            }
        }

        if (!empty($alerts)) {
            $message = implode("\n\n", $alerts);

            // Kirim event untuk notifikasi otomatis
            event(new GasThresholdExceeded($message));

            $whatsappService = new WhatsAppService();
            $whatsappService->sendGasAlert($message);
        }

        return response()->json([
            'message' => 'Data berhasil disimpan dan diperiksa',
            'data' => $sensorData
        ]);
    }

    public function checkGasLevel()
{
    $latestSensorData = SensorData::latest()->first();
    if (!$latestSensorData) {
        return response()->json(['error' => 'Tidak ada data sensor']);
    }

    $thresholds = [
        // 'mq4_value' => 300,
        'mq6_value' => 300,
        'mq8_value' => 300
    ];

    $sensorNames = [
        'mq6_value' => 'Propane/Butane Gas',
        'mq8_value' => 'Hydrogen Gas'
    ];

    $alerts = [];

    foreach ($thresholds as $sensor => $threshold) {
        $sensorValue = $latestSensorData->$sensor ?? 0;
        if ($sensorValue >= $threshold) {
            $sensorName = $sensorNames[$sensor] ?? $sensor;
            $alerts[] = "⚠️ *Peringatan!* Sensor *{$sensorName}* mendeteksi gas berbahaya! \n🔥 Level: *{$sensorValue}* ppm 🚨";
        }
    }

    if (!empty($alerts)) {
        $message = implode("\n\n", $alerts);
        event(new GasThresholdExceeded($message)); // 🔥 Trigger event
    }

    return response()->json([
        // 'mq4_value' => $latestSensorData->mq4_value,
        'mq6_value' => $latestSensorData->mq6_value,
        'mq8_value' => $latestSensorData->mq8_value
    ]);
    }
}
