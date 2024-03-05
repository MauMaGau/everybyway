<?php

namespace App\Models;

use App\DTOs\Geo;
use App\Events\PingSaving;
use App\Helpers\GeoHelper;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int id
 * @property int user_id
 * @property User user
 * @property int bimble_id
 * @property Bimble bimble
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

    protected $casts = [
        'is_home_area' => 'boolean',
    ];

    protected $hidden = [
        'data',
    ];

    protected $dispatchesEvents = ['saving' => PingSaving::class];


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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bimble(): BelongsTo
    {
        return $this->belongsTo(Bimble::class);
    }


}
