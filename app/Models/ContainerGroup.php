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
        'to_port_id',
        'is_email_sent',
        'arrival_time',
        'invoice_file',
        'thc_invoice',
        'thc_cost',
        'thc_agent',
        'title_in_office',
        'trt_payed',
        'thc_payed',
        'is_green',
        'terminal',
        'cont_status',
        'est_open_date',
        'opened',
        'open_payed',
        'remark',
    ];

    public function cars()
    {
        return $this->belongsToMany(Car::class, 'container_group_container');
    }
}
