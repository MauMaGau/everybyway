<?php

namespace App\Helpers;

use App\DTOs\Geo;

class GeoHelper
{
    public static function distance(Geo $a, Geo $b): float
    {
        $earthRadius = env('DISTANCE_UNITS') === 'm'? 6371000: 3959; // metres or miles

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

    public static function protectHomeArea(Geo $location): Geo
    {
        $userHome = new Geo(env('HOME_LAT'), env('HOME_LON'));
        if (self::distance($location, $userHome) < env('HOME_RADIUS')) {
            return self::anonymiseGeo($location);
        }

        return $location;
    }

    public static function anonymiseGeo(Geo $location): Geo
    {
        // This is bad. It'll end up creating a circle perfectly surrounding my house.
        // Maybe set to the nearest 'grid square'?
        $noise = env('HOME_RADIUS');
        $location->lat += mt_rand(-$noise, $noise) / 100;
        $location->lon += mt_rand(-$noise, $noise) / 100;
        return $location;
    }
}
