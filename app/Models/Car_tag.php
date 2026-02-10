<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car_tag extends Model
{
    /** @use HasFactory<\Database\Factories\CarTagFactory> */
    use HasFactory;

    protected $fillable = [
        'car_id',
        'tag_id',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
    
}
