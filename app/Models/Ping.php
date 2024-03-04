<?php

namespace App\Models;

use App\DTOs\Geo;
use App\Helpers\GeoHelper;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int userId
 * @property string data
 * @property float lat
 * @property float lon
 * @property int distance_from_last_ping
 * @property bool is_home_area
 * @property Geo geo
 */
class Ping extends Model
{
    use HasFactory;

    protected $hidden = [
        'data',
    ];

    protected static function booted(): void
    {
        // Rather than using an Event, it's more readable to keep any property manipulation within the model
        static::saving(function (Ping $ping) {
            if (GeoHelper::distance(
                new Geo(env('HOME_LAT'), env('HOME_LON')),
                $ping->geo
            ) < env('HOME_RADIUS')) {
                $ping->is_home_area = true;
            }
        });
    }

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

    public function scopeHideHome()
    {

    }


}
