<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CustomerBalance extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected  $guarded = [];


    protected $casts = [
        'carpayment_date' => 'datetime',
        'date' => 'date',
        'balance_fill_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

}
