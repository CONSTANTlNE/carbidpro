<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortCity extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'price', 'country_id'];


    public function cars(){
        $this->hasMany(Car::class);
    }

    public function country(){
        return $this->belongsTo(Country::class);
    }
}
