<?php

namespace App\Http\Controllers;

use Twilio\Rest\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\WhatsAppService;
use Illuminate\Auth\Events\Registered;
use App\Listeners\SendWhatsAppWelcomeMessage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:50',
            'password' => 'required|max:50',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->remember)) {

            return redirect('/');
        }
        return back()->with('failed', 'Email atau password salah.');
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|email|max:50|unique:users',
            'phone_number' => 'required|max:50|unique:users',
            'password' => 'required|max:50|min:8',
            'confirm_password' => 'required|max:50|min:8|same:password',
        ]);

        $request['status'] = "active";
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password), // Hash password
            'status' => 'active',
        ]);
        Auth::login($user);
        $whatsappService = new WhatsAppService();
        $whatsappService->sendSandboxInstruction($user);
        event(new \Illuminate\Auth\Events\Registered($user)); // Trigger event
        return redirect('/')->with('success', 'Registrasi berhasil!');
    }

    protected function registered(Request $request, $user)
    {
        $this->sendWhatsAppNotification($user->whatsapp_number, $user->name);
    }
    
    private function sendWhatsAppNotification($whatsappNumber, $userName)
    {
        $sid    = env("TWILIO_SID");
        $token  = env("TWILIO_AUTH_TOKEN");
        $twilio = new Client($sid, $token);
    
        $message = "🎉 *Selamat Datang, $userName!* 🎉\n\n".
                   "✅ Anda telah berhasil mendaftar.\n".
                   "🚀 Sekarang Anda akan menerima notifikasi otomatis terkait pemantauan gas.";
    
        $twilio->messages->create(
            "whatsapp:$whatsappNumber",
            [
                "from" => env("TWILIO_WHATSAPP_FROM"),
                "body" => $message
            ]
        );
    }

    public function logout(){
        Auth::logout(Auth::user());
        return redirect('/login');
    }
}
