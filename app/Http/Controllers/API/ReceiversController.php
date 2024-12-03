<?php

namespace App\Http\Controllers\API;

// ** utils
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// ** models
use App\Models\Devices;
use App\Models\Receivers;

class ReceiversController extends Controller
{
    public function sensor(Request $request)
    {
        $appid = $request->header('appid', '');
        $appsecret = $request->header('appsecret', '');
        $body = $request->all();

        $device = Devices::where('appid', $appid)
                         ->where('appsecret', $appsecret)
                         ->whereNull('deleted_at')
                         ->first();

        if ($device) {
            $data = [
                'device_id' => $device->id,
                'type'      => 'sensor',
                'body'      => json_encode($body)
            ];

            $record = Receivers::simpan($data);

            return response()->json([
                'status' => true,
                'message' => 'Data has been received.',
                'body' => $body
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Forbidden.'
        ], 403);
    }

    public function webhook($appid = '', $appsecret = '', Request $request)
    {
        $body = $request->all();

        $device = Devices::where('appid', $appid)
                         ->where('appsecret', $appsecret)
                         ->whereNull('deleted_at')
                         ->first();

        if ($device) {
            $data = [
                'device_id' => $device->id,
                'type'      => 'sensor',
                'body'      => json_encode($body)
            ];

            $record = Receivers::simpan($data);

            return response()->json([
                'status' => true,
                'message' => 'Data has been received.',
                'body' => $body
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Forbidden.'
        ], 403);
    }
}
