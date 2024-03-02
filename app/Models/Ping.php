<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int userId
 * @property string data
 * @property float lat
 * @property float lon
 */
class Ping extends Model
{
    use HasFactory;

    protected $hidden = [
        'data',
    ];
}
