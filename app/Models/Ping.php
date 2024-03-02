<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int userId
 * @property string data
 */
class Ping extends Model
{
    use HasFactory;
}
