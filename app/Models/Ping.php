<?php

namespace App\Models;

use App\DTOs\Geo;
use App\Events\PingCreated;
use App\Events\PingCreating;
use App\Helpers\GeoHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int    id
 * @property int    user_id
 * @property User   user
 * @property int    bimble_id
 * @property Bimble bimble
 * @property string data
 * @property float  lat
 * @property float  lon
 * @property int    distance_from_last_ping
 * @property bool   is_home_area
 * @property Carbon $captured_at
 * @property ?Ping  previousPing
 * @property Geo    geo
 */
class Ping extends Model
{
    use HasFactory;

    protected ?Ping $previousPing = null;

    protected $casts = [
        'captured_at' => 'datetime',
        'is_home_area' => 'boolean',
    ];

    protected $hidden = [
        'data',
    ];

    protected $dispatchesEvents = ['creating' => PingCreating::class, 'created' => PingCreated::class];

    public function geo(): Attribute
    {
        return Attribute::make(
            get: fn ($value, array $attributes) => new Geo($attributes['lat'], $attributes['lon']),
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bimble(): BelongsTo
    {
        return $this->belongsTo(Bimble::class);
    }

    public function scopePublic(Builder $query): void
    {
        $query->where('is_home_area', false);
    }

    /*
     * singleton-ish method to get the ping previous to this one
     * */
    public function previousPing(): ?Ping
    {
        if ($this->previousPing) {
            return $this->previousPing;
        }

        $previousPing = self::where('user_id', $this->user_id)->where('captured_at', '<=', $this->captured_at)->where('id', '!=', $this->id)->latest()->first();

        if (!$previousPing) {
            return null;
        }

        $this->previousPing = $previousPing;

        return $this->previousPing;
    }

    public function isPingInHomeArea(): bool
    {
        return $this->user->homeAreas->first(function ($homeArea) {
            return GeoHelper::distance($homeArea->geo, $this->geo) < env('HOME_RADIUS');
        }) !== null;
    }
}
