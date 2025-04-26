<?php

namespace App\Services;

use App\Models\WhatsAppNotification;
use App\Models\User;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
    }

    public function sendSandboxInstruction(User $user)
    {
        $recipient = $user->phone_number;

        $message = "👋 Halo, *{$user->name}*! \n\n".
                   "Untuk menerima notifikasi WhatsApp dari sistem kami, silakan lakukan langkah berikut:\n\n".
                   "1️⃣ Buka WhatsApp Anda.\n".
                   "2️⃣ Kirim pesan *JOIN gain-basic* ke *+1 415 523 8886*.\n\n".
                   "Setelah itu, Anda akan mulai menerima notifikasi otomatis dari sistem kami. ✅";

        try {
            $this->twilio->messages->create(
                "whatsapp:" . $recipient,
                [
                    "from" => env("TWILIO_WHATSAPP_FROM"),
                    "body" => $message
                ]
            );

            // Simpan notifikasi ke database
            WhatsAppNotification::create([
                'user_id' => $user->id,
                'recipient_number' => $recipient,
                'message' => $message,
                'status' => 'sent'
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('WhatsApp Error: ' . $e->getMessage());

            WhatsAppNotification::create([
                'user_id' => $user->id,
                'recipient_number' => $recipient,
                'message' => $message,
                'status' => 'failed'
            ]);

            return false;
        }
    }

    public function sendMessage(User $user, $message)
    {
        $recipient = $user->phone_number;

        try {
            // Kirim pesan melalui Twilio
            $this->twilio->messages->create(
                "whatsapp:" . $recipient,
                [
                    "from" => env("TWILIO_WHATSAPP_FROM"),
                    "body" => $message
                ]
            );
    
            // Jika berhasil, simpan ke database
            WhatsAppNotification::create([
                'user_id' => $user->id, // Tambahkan user_id
                'recipient_number' => $recipient,
                'message' => $message,
                'status' => 'sent'
            ]);
    
            return true;
        } catch (\Exception $e) {
            Log::error('WhatsApp Error: ' . $e->getMessage());
    
            // Jika gagal, tetap simpan ke database dengan status 'failed'
            WhatsAppNotification::create([
                'user_id' => $user->id, // Tambahkan user_id
                'recipient_number' => $recipient,
                'message' => $message,
                'status' => 'failed'
            ]);
    
            return false;
        }
    }

    public function processPendingMessages()
    {
        // Ambil semua pesan yang statusnya masih 'pending'
        $pendingMessages = WhatsAppNotification::where('status', 'pending')->get();

        foreach ($pendingMessages as $notification) {
            $recipient = $notification->recipient_number;
            $message = $notification->message;

            try {
                // Kirim WhatsApp melalui Twilio
                $this->twilio->messages->create(
                    "whatsapp:" . $recipient,
                    [
                        "from" => env("TWILIO_WHATSAPP_FROM"),
                        "body" => $message
                    ]
                );

                // Jika sukses, update status jadi 'sent'
                $notification->update(['status' => 'sent']);
                Log::info("WhatsApp sent to: $recipient");
            } catch (\Exception $e) {
                Log::error('WhatsApp Error: ' . $e->getMessage());

                // Jika gagal, update status jadi 'failed'
                $notification->update(['status' => 'failed']);
            }
        }
    }

    public function sendGasAlert($message)
    {
        $users = WhatsAppNotification::distinct()->get(['recipient_number']);
    
        foreach ($users as $user) {
            $this->sendWhatsAppMessage($user->recipient_number, $message);
        }
    }
    private function sendWhatsAppMessage($recipientNumber, $message)
    {
    try {
        $this->twilio->messages->create(
            "whatsapp:$recipientNumber",
            [
                "from" => env("TWILIO_WHATSAPP_FROM"),
                "body" => $message
            ]
        );
        Log::info("WhatsApp message sent to: $recipientNumber");
    } catch (\Exception $e) {
        Log::error("Failed to send WhatsApp message: " . $e->getMessage());
    }
    }}