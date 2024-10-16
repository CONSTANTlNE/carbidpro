<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
    public $sortable = ['customer', 'dispatcher', 'car_statuses'];  // Add other sortable columns as needed

    protected $fillable = [
        'make_model_year',
        'status',
        'lot',
        'vin',
        'percent',
        'type_of_fuel',
        'title',
        'gate_or_member',
        'auction',
        'load_type',
        'from_state',
        'to_port_id',
        'sub_total',
        'payed',
        'amount_due',
        'vehicle_owner_name',
        'owner_id_number',
        'owner_phone_number',
        'container_number',
        'images',
        'invoice_file',
        'title_received',
        'key',
        'record_color',
        'comment',
        'is_deleted',
        'balance_accounting',
        'warehouse',
        'internal_shipping',
        'company_name',
        'contact_info',
        'pickup_dates',
        'storage',
        'storage_cost',
        'title_delivery',
        'payment_method',
        'payment_address',
        'customer_id',
        'dispatch_id',
        'zip_code',
        'arrival_time',
        'title_take',
        'remark',
        'bill_of_loading',
        'container_images',
    ];

    public function statusRelation()
    {
        return $this->belongsTo(CarStatus::class, 'status', 'id');
    }

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
        return $this->belongsTo(PortCity::class, 'to_port_id');
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
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function CarStatus()
    {
        return $this->hasOne(CarStatus::class, 'id');
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
}
