<?php

namespace App\Listeners;

use App\Events\GasThresholdExceeded;
use App\Services\WhatsAppService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendGasAlert implements ShouldQueue
{
    use InteractsWithQueue;

    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    public function handle(GasThresholdExceeded $event)
    {
        Log::info("🚨 Mengirim notifikasi WhatsApp: " . $event->message);
        $this->whatsappService->sendGasAlert($event->message);
    }
}
