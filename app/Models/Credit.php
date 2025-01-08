<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Credit extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'issue_or_payment_date' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function balance()
    {
        return $this->belongsTo(CustomerBalance::class);
    }


}
