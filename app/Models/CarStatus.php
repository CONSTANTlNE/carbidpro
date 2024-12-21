<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CarStatus extends Model
{
    use HasFactory;

    public function cars()
    {
        return $this->hasMany(Car::class, );
    }



    protected static function boot()
    {
        parent::boot();

        static::creating(function ($status) {
            if (!$status->slug) {
                $status->slug = Str::slug($status->name);
            }

        });


        static::updating(function ($status) {
            if ($status->isDirty('name')) {
                $status->slug = Str::slug($status->name);
            }
        });
    }
}
