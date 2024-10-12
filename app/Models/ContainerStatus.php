<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContainerStatus extends Model
{
    use HasFactory;

    public function cars()
    {
        return $this->hasMany(Car::class, 'status', 'id');
    }
}
