<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_active'];

    public function locations()
    {
        return $this->hasMany(Location::class);
    }
}
