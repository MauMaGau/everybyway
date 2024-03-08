<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property int id
 * @property int started_at
 * @property int ended_at
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
}
