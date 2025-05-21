<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SensorData;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Log;

class CheckGasLevel extends Command
{
    protected $signature = 'check:gas-level';
    protected $description = 'Cek level gas dan kirim peringatan jika melebihi ambang batas';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $latestSensorData = SensorData::latest()->first();
        if (!$latestSensorData) {
            Log::warning("Tidak ada data sensor untuk dicek.");
            return;
        }

        $thresholds = [
            // 'mq4_value' => 300,
            'mq6_value' => 20000,
            'mq8_value' => 8000
        ];

        $sensorNames = [
            'mq6_value' => 'Propane/Butane Gas',
            'mq8_value' => 'Hydrogen Gas'
        ];

        $whatsappService = new WhatsAppService();
        $alerts = [];

        foreach ($thresholds as $sensor => $threshold) {
            $sensorValue = $latestSensorData->$sensor ?? 0;
            if ($sensorValue >= $threshold) {
                $sensorName = $sensorNames[$sensor] ?? $sensor;
                $alerts[] = "⚠️Sensor *{$sensorName}* mendeteksi gas! \n🔥 Level: *{$sensorValue}* ppm 🚨";
            }
        }

        if (!empty($alerts)) {
            $message = implode("\n\n", $alerts);
            Log::info("🚨 Mengirim peringatan WhatsApp: $message");
            $whatsappService->sendGasAlert($message);
        }
    }
}
