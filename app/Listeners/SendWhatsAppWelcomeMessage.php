<?php

namespace App\Listeners;

use App\Models\User;
use App\Services\WhatsAppService;
use App\Models\WhatsAppNotification;
use Illuminate\Auth\Events\Registered;

class SendWhatsAppWelcomeMessage
{
    protected $whatsappService;
    public function __construct(WhatsAppService $whatsappService)
        {
            $this->whatsappService = $whatsappService;
        }
    
    

    public function handle(Registered $event)
    {
        $user = $event->user;

        if (!empty($user->phone_number)) {
            $message = "👋 Selamat datang, {$user->name}!\n\n" .
                       "Akun Anda telah berhasil terdaftar.\n" .
                       "Kami akan mengirimkan notifikasi jika ada bahaya gas.\n\n" .
                       "🔥 Tetap aman dan waspada!";

            // Simpan data ke tabel whatsapp_notifications sebelum dikirim
            $notification = WhatsAppNotification::create([
                'user_id' => $user->id,
                'recipient_number' => $user->phone_number,
                'message' => $message,
                'status' => 'pending', // Status awal pending
            ]);

            // Kirim WhatsApp menggunakan Twilio
            $whatsappService = new WhatsAppService();
            // Kirim pesan welcome + instruksi daftar Twilio Sandbox
            $this->whatsappService->sendSandboxInstruction($user);
            $sent = $whatsappService->sendMessage($user, $message);

            // Update status jika berhasil atau gagal
            $notification->update(['status' => $sent ? 'sent' : 'failed']);
        }
    
        

        
    
       
    }
}
