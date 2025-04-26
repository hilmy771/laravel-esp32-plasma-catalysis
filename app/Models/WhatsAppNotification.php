<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsAppNotification extends Model
{
    use HasFactory;

    protected $table = 'whats_app_notifications';

    protected $fillable = ['user_id', 'recipient_number', 'message', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

