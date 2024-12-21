<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Setting extends Model
{
    use HasTranslations ;

    protected $primaryKey = 'key';
    protected $keyType = 'string';
    public $incrementing = false;
    public $translatable = ['value'];


    protected $guarded=[];

}
