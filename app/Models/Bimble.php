<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * @property int id
 * @property Carbon started_at
 * @property Carbon ended_at
 * @property Collection pings
 * @property User user
 */
class Bimble extends Model
{
    use HasFactory;

    protected $fillable = ['started_at', 'ended_at'];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function pings(): HasMany
    {
        return $this->hasMany(Ping::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeHavingPublicPings(Builder $query): void
    {
        $query->whereHas('pings', function ($query) {
            $query->where('is_home_area', false);
        });
    }

    public function scopeWithPublicPings(Builder $query): void
    {
        $query->withWhereHas('pings', function ($query) {
            $query->where('is_home_area', false);
        });
    }
}
