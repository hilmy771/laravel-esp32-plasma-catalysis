<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Device;
use Illuminate\Support\Str;

class DeviceController extends Controller
{
    // Menampilkan halaman daftar perangkat
    public function index()
    {
        $devices = Device::all();

        // 2) Ambil daftar ruangan unik dari kolom room_name
        $rooms = Device::select('room_name')
                        ->distinct()
                        ->orderBy('room_name')
                        ->pluck('room_name'); // akan jadi Collection string
        return view('devices', compact('devices','rooms')); // Mengarah ke devices.blade.php

    }


    // Menyimpan perangkat baru 
    public function store(Request $request)
    {
        $request->validate([
            'room_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'sensors' => 'required|array|min:1', // Wajib pilih minimal 1 sensor
            'sensors.*' => 'in:mq6,mq8' // Pastikan nilai hanya dari daftar ini
        ], [
            'sensors.required' => 'Anda harus memilih setidaknya satu sensor.',
            'sensors.min' => 'Anda harus memilih setidaknya satu sensor.',
        ]);

        // Generate token unik untuk perangkat
        $token = Str::random(32);

        // Simpan ke database
        Device::create([
            'room_name' => $request->room_name,
            'name' => $request->name,
            'sensors' => json_encode($request->sensors), // Simpan sebagai JSON
            'token' => $token
        ]);

        return redirect()->route('devices.index')->with('success', 'Perangkat berhasil didaftarkan.');
    }


    public function destroy($id)
    {
        $device = Device::findOrFail($id);
        $device->delete();

        return redirect()->route('devices.index')->with('success', 'Perangkat berhasil dihapus');
    }

    public function showAllDevices()
    {
        $devices = Device::all();
        return response()->json($devices);
    }

    public function getDevicesByRoom(Request $request)
    {
        $request->validate([
            'room_name' => 'required|string|exists:devices,room_name'
        ]);

        $devices = Device::where('room_name', $request->room_name)
                        ->get(['id','name']);

        return response()->json(['devices' => $devices]);
    }



}
