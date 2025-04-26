<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SensorData extends Model
{
    use SoftDeletes, HasFactory;

    // Nama tabel jika berbeda dari default
    protected $table = 'sensor_data';

    // Kolom yang bisa diisi
    protected $fillable = [
        'device_id',
        // 'mq4_value',
        'mq6_value',
        'mq8_value',
    ];

    public $timestamps = true; // Pastikan ini ada

    // Relasi ke Device
    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id');
    }
}

