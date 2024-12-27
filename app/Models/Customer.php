<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class Customer extends Authenticatable implements HasMedia
{
    use HasFactory;
    use HasRoles;
    use SoftDeletes;
    use InteractsWithMedia;
    protected $guarded=[];


    public function cars(): HasMany{
        return $this->hasMany(Car::class);
    }

    public function credits ()
    {
        return $this->hasMany(Credit::class);
    }

    public function balances()
    {
        return $this->hasMany(CustomerBalance::class);
    }
}
