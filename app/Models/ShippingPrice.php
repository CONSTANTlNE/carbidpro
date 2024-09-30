<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingPrice extends Model
{
    use HasFactory;

    protected $casts = [
        'auction_id' => 'array',
    ];
    protected $fillable = ['from_location_id', 'to_port_id', 'auction_id', 'price'];



    public function location()
    {
        return $this->belongsTo(Location::class, 'from_location_id');
    }

    public function port()
    {
        return $this->belongsTo(Port::class, 'to_port_id');
    }

    public function auction()
    {
        return $this->belongsToMany(Auction::class, 'shipping_prices', 'auction_id');
    }

    public function getAuctionNamesAttribute()
    {
        return Auction::whereIn('id', $this->auction_id)->pluck('name')->toArray();
    }
    public function getLocationNameAttribute()
    {
        return optional($this->location)->name; // Handle null values
    }
}
