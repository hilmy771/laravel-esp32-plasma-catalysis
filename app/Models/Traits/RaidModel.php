<?php

namespace App\Models\Traits;

use App\Models\User;

trait RaidModel
{
    public static function boot()
    {
        parent::boot();

        if (auth()->check()) {
            if (\Schema::hasColumn(with(new static )->getTable(), 'updated_by')) {
                static::saving(function ($table) {
                    $table->updated_by = auth()->user()->id;
                });
            }

            if (\Schema::hasColumn(with(new static )->getTable(), 'created_by')) {
                static::creating(function ($table) {
                    $table->created_by = auth()->user()->id;
                });
            }

            if (\Schema::hasColumn(with(new static )->getTable(), 'deleted_by')) {
                static::deleting(function ($table) {
                    $table->deleted_by = auth()->user()->id;
                });
            }
        }
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
