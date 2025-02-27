<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Title extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function customers()
    {
        return $this->belongsToMany(Customer::class);
    }



    public static function boot()
    {
        parent::boot();

        static::creating(function ($title) {
            $originalSlug = Str::slug($title->name);
            $slug = $originalSlug;
            $count = 1;

            while (static::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }

            $title->slug = $slug;
        });
    }


}
