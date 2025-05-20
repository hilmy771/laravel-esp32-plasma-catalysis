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
        // 1) Validasi input
        $request->validate([
            'room_name' => 'required|string|exists:devices,room_name',
            'device_id' => 'required|exists:devices,id',
        ], [
            'room_name.required' => 'Nama ruangan wajib diisi.',
            'room_name.exists'   => 'Ruangan tidak ditemukan.',
            'device_id.required' => 'Perangkat wajib dipilih.',
            'device_id.exists'   => 'Perangkat tidak valid.',
        ]);

        // 2) Bangun query dengan kedua filter
        $query = SensorData::query()
            // pastikan device milik room_name yang diminta
            ->whereHas('device', function($q) use ($request) {
                $q->where('room_name', $request->room_name);
            })
            ->where('device_id', $request->device_id)
            ->orderBy('created_at', 'desc')
            ->take(5);

        // 3) Ambil data
        $sensorData = $query->get();

        // 4) Format dan kembalikan JSON
        return response()->json(
            $sensorData->map(fn($data) => [
                'mq6_value'  => $data->mq6_value,
                'mq8_value'  => $data->mq8_value,
                'created_at' => $data->created_at->toISOString(),
            ])
        );
    }

    // Menampilkan semua data sensor untuk halaman Data Sensor
    public function getAllSensorData(Request $request)
    {
        $query = SensorData::with('device');

        // Filter ruangan
        if ($request->filled('room_name')) {
            $query->whereHas('device', function ($q) use ($request) {
                $q->where('room_name', $request->room_name);
            });
        }

        // Filter perangkat
        if ($request->filled('device_id')) {
            $query->where('device_id', $request->device_id);
        }

        if ($request->filled('since')) {
            $query->where('created_at', '>', $request->since);
        }

        // Filter tanggal
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $sensorData = $query->orderByDesc('created_at')->paginate(20);

        // Ambil semua ruangan unik dan perangkat yang tersedia
        $rooms = Device::distinct()->pluck('room_name');
        $devices = Device::when($request->room_name, function ($q) use ($request) {
            return $q->where('room_name', $request->room_name);
        })->get();

        return view('sensor_data', compact('sensorData', 'devices', 'rooms'));
    }


    // Menyimpan data sensor ke dalam database
    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required|string|exists:devices,token',
            // 'gas_value_mq4' => 'nullable|numeric',
            'gas_value_mq6' => 'nullable|numeric',
            'gas_value_mq8' => 'nullable|numeric',
        ]);

        $device = Device::where('token', $request->token)->first();

        if (!$device) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        $sensorData = SensorData::create([
            'device_id' => $device->id,
            // 'mq4_value' => $request->gas_value_mq4,
            'mq6_value' => $request->gas_value_mq6 ?? null,
            'mq8_value' => $request->gas_value_mq8 ?? null,
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
                $alerts[] = "Gas {$sensorName} terdeteksi melebihi ambang batas! Segera lakukan penanganan kebocoran di laboratorium.";
            }
        }

        $alertMessage = null;

        if (!empty($alerts)) {
            $alertMessage = implode("\n\n", $alerts);
        
            // Kirim event dan WhatsApp
            event(new GasThresholdExceeded($alertMessage));
        
            $whatsappService = new WhatsAppService();
            $whatsappService->sendGasAlert($alertMessage);
        }
        
        // Update the sensor data with alert (if any)
        $sensorData->gas_alert = $alertMessage;
        $sensorData->save();

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
        'mq6_value' => 300,
        'mq8_value' => 300
    ];

    $sensorNames = [
        'mq6_value' => 'Propane/Butane Gas',
        'mq8_value' => 'Hydrogen Gas'
    ];

    $alerts = [];
    $alertTriggered = false;

    for ($i = 0; $i < 5; $i++) {
        foreach ($thresholds as $sensor => $threshold) {
            $sensorValue = $latestSensorData->$sensor ?? 0;
            if ($sensorValue >= $threshold) {
                $sensorName = $sensorNames[$sensor] ?? $sensor;
                $alerts[] = [
                    'message' => "Gas {$sensorName} terdeteksi melebihi ambang batas! Segera lakukan penanganan kebocoran di laboratorium.",
                    'timestamp' => now()->toDateTimeString(),
                ];
                $alertTriggered = true;
            }
        }
    }
    

    // Get latest 5 alerts for dropdown (not null)
    $recentAlerts = SensorData::whereNotNull('gas_alert')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->pluck('gas_alert', 'created_at')
        ->map(function ($message, $timestamp) {
            return [
                'message' => $message,
                'timestamp' => \Carbon\Carbon::parse($timestamp)->toDateTimeString()
            ];
        })
        ->values();

        return response()->json([
            'alerts' => $alerts,
            'alertTriggered' => $alertTriggered,
            'mq6_value' => $latestSensorData->mq6_value,
            'mq8_value' => $latestSensorData->mq8_value,
        ]);
}
}
