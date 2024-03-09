<?php

namespace App\Models;

use App\DTOs\Geo;
use App\Helpers\GeoHelper;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int id
 * @property int bimble_id
 * @property string data
 * @property float lat
 * @property float lon
 * @property int distance_from_last_ping
 * @property bool is_home_area
 * @property Geo geo
 */
class HomeArea extends Model
{
    use HasFactory;

    protected $fillable = ['lat', 'lon'];

    public function geo(): Attribute // @TODO: This should be a trait?
    {
        return Attribute::make(
            get: fn ($value, array $attributes) => new Geo($attributes['lat'], $attributes['lon']),
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
