<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'auction_id',
        'is_active',
    ];

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }
    
}
