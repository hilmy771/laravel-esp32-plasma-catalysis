<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Receivers extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trans_receivers';

    protected $fillable = [
        'device_id',
        'type',
        'body',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Devices::class, 'device_id');
    }

    public static function simpan($request)
    {
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
