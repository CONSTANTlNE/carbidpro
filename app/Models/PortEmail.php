<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortEmail extends Model
{
    use HasFactory;
    protected $fillable = ['email', 'port_id'];

    public function port()
    {
        return $this->belongsTo(Port::class, 'port_id');
    }


}
