<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ContainerGroup extends Model  implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;

    protected $guarded = [];

    public function cars()
    {
        return $this->belongsToMany(Car::class, 'container_group_container');
    }

    public function port(){
        return $this->belongsTo(Port::class, 'to_port_id');

    }

    public function line(){
        return $this->belongsTo(ShippingLine::class, 'shipping_line_id');
    }

    public function warehouse(){

        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

}
