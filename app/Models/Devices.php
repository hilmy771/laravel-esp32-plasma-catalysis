<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\Traits\RaidModel;

class Devices extends Model
{
    use HasFactory, SoftDeletes, RaidModel;

    protected $table = 'trans_devices';

    protected $fillable = [
        'name',
        'appid',
        'appsecret',
    ];

    public static function generateappid()
    {
        $result = '';
        $repeat = true;
        while ($repeat) {
            $result = strtoupper(Str::random(8));
            $record = self::where('appid', $result)->first();
            if (is_null($record)) {
                $repeat = false;
            }
        }

        return $result;
    }

    public static function generateappsecret()
    {
        $result = '';
        $repeat = true;
        while ($repeat) {
            $result = strtoupper(Str::random(16));
            $record = self::where('appsecret', $result)->first();
            if (is_null($record)) {
                $repeat = false;
            }
        }

        return $result;
    }

    public static function simpan($request)
    {
        $appid = self::generateappid();
        $appsecret = self::generateappsecret();
        $request = array_merge($request, [
            'appid'         => $appid,
            'appsecret'     => $appsecret
        ]);

        $record = new self;
        $record->fill($request);
        $record->save();

        return $record;
    }

    public function ubah($request)
    {
        $this->fill($request);
        $this->save();
    }
}
