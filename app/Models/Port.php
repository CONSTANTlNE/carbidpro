<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Port extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'price',
    ];


    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    public function state(){
     return   $this->hasOne(State::class );
    }
}
