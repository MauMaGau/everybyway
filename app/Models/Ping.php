<?php

namespace App\Models;

use App\DTOs\Geo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int userId
 * @property string data
 * @property float lat
 * @property float lon
 * @property int distance
 * @property Geo geo
 */
class Ping extends Model
{
    use HasFactory;

    protected $hidden = [
        'data',
    ];

    public function geo(): Attribute
    {
        return Attribute::make(
            get: fn ($value, array $attributes) => new Geo($attributes['lat'], $attributes['lon']),
        );
    }

//    public function lat(): Attribute
//    {
//        return Attribute::make(
//            get: fn (float $value, array $attributes) => GeoHelper::protectHomeArea($this->geo)->lat,
//        );
//    }
//
//    public function lon(): Attribute
//    {
//        return Attribute::make(
//            get: fn (float $value, array $attributes) => GeoHelper::protectHomeArea($this->geo)->lon,
//        );
//    }


}
