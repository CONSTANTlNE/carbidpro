<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContainerGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_name',
        'container_id',
        'photo',
        'cost',
        'booking_id',
    ];

    public function cars()
    {
        return $this->belongsToMany(Car::class, 'container_group_container');
    }
}
