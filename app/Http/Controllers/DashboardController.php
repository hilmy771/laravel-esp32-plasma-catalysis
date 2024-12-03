<?php

namespace App\Http\Controllers;

// ** utils
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

// ** requests
use Illuminate\Http\Request;

// ** models
use App\Models\Devices;
use App\Models\Receivers;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        return view('dashboard.index', [
            'title' => 'Dashboard',
            'devices' => Devices::where('created_by', $user->id)->get()
        ]);
    }

    public function type(Devices $device, $type = '')
    {
        if (!in_array($type, ['suhu', 'kelembaban'])) {
            return response()->json([
                'status' => false,
                'message' => 'Bad Request.'
            ], 400);
        }

        $data = Receivers::where('device_id', $device->id)
                         ->where('body', 'like', "%\"{$type}\"%")
                         ->orderBy('id', 'desc')
                         ->take(15)
                         ->get()
                         ->reverse()
                         ->values();
        
        $labels = [];
        $values = [];
        foreach ($data as $d) {
            $body = (array)json_decode($d->body);
            $labels[] = date('H:i:s', strtotime($d->created_at));
            $values[] = $body[$type];
        }
        
        return response()->json([
            'status' => true,
            'message' => 'OK.',
            'labels' => $labels,
            'values' => $values
        ], 200);
    }
}
