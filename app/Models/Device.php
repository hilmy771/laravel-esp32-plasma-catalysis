<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SensorData;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_name', 
        'name', 
        'sensors', 
        'token'
    ];

    protected $casts = [
        'sensors' => 'array', // Otomatis ubah JSON ke array saat diambil dari database
    ];

    public function sensorData()
    {
        return $this->hasMany(SensorData::class, 'device_id');
    }
}
