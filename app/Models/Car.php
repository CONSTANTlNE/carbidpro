<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Illuminate\Database\Eloquent\Builder;

class Car extends Model implements HasMedia, Sortable
{
    use SortableTrait;
    use HasFactory;
    use InteractsWithMedia;
    use SoftDeletes;

    public $sortable = ['customer', 'dispatcher', 'car_statuses'];  // Add other sortable columns as needed

    protected $guarded=[];

    // Example of a relationship, assuming you have a 'ports' table for 'to_port_id'


    public function port()
    {
        return $this->belongsTo(Port::class, 'to_port_id');
    }

    public function state()
    {
        return $this->belongsTo(Location::class, 'from_state');
    }

    public function toPort()
    {
        return $this->belongsTo(PortCity::class, 'to_port_id', 'id');
    }

    public function loadType()
    {
        return $this->belongsTo(LoadType::class, 'load_type');
    }

    public function Auction()
    {
        return $this->hasOne(Auction::class, 'id', 'auction');
    }

    public function dispatch()
    {
        return $this->hasOne(User::class, 'id', 'dispatch_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function CarStatus()
    {
        return $this->belongsTo(CarStatus::class,'car_status_id','id');
    }

//    public function payments(){
//        return $this->hasMany(CarPayment::class);
//    }

    public function payments(){
        return $this->hasMany(CustomerBalance::class);
    }

    public function scopeSortByCustomer(Builder $query, $direction = 'asc')
    {
        return $query->whereHas('customer', function ($query) use ($direction) {
            $query->orderBy('contact_name', $direction);
        });
    }

    /**
     * Scope for sorting by dispatcher name.
     */
    public function scopeSortByDispatcher(Builder $query, $direction = 'asc')
    {
        return $query->whereHas('dispatch', function ($query) use ($direction) {
            $query->orderBy('name', $direction);
        });
    }

    public function groups()
    {
        return $this->belongsToMany(ContainerGroup::class, 'container_group_container');
    }

    public function credit() {

        return $this->hasMany(Credit::class);

    }

    /**
     * Check if credit is given for car and retrieve latest payment details
     */
    public function latestCredit(): HasOne
    {
        return $this->hasOne(Credit::class)->latestOfMany('issue_or_payment_date');
    }

    public function firstCredit(): HasOne
    {
        return $this->hasOne(Credit::class)->oldestOfMany('issue_or_payment_date');
    }

    // same as broker
    public function dispatcher()
    {
        return $this->belongsTo(User::class, 'dispatch_id');
    }

    public function warehouse(){
        return $this->belongsTo(Warehouse::class);
    }

}
