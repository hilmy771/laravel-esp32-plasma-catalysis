<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'sensors', 'token'];

    protected $casts = [
        'sensors' => 'array', // Otomatis ubah JSON ke array saat diambil dari database
    ];
}
