<?php

namespace App\Helpers;

use App\DTOs\Geo;

class GeoHelper
{
    public static function distance(Geo $a, Geo $b): int
    {
//        $earthRadius = env('DISTANCE_UNITS') === 'm'? 6371000: 3959; // metres or miles
        $earthRadius = 6371000; // Record everything in metres, add display adaptor later

        // convert from degrees to radians
        $latFrom = deg2rad($a->lat);
        $lonFrom = deg2rad($a->lon);
        $latTo = deg2rad($b->lat);
        $lonTo = deg2rad($b->lon);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
             pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);
        return $angle * $earthRadius;
    }
}
