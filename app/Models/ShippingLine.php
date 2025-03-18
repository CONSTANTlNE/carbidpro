<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingLine extends Model
{



    public function containers()
    {
        return $this->hasMany(ContainerGroup::class);
    }
}
