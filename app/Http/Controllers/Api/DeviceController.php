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
        return view('devices', compact('devices')); // Mengarah ke devices.blade.php
    }


    // Menyimpan perangkat baru
    public function store(Request $request)
    {
        $request->validate([
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

}
