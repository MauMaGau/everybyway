<?php

namespace App\DTOs;

class Geo
{
    public float $lat;
    public float $lon;

    public function __construct(float $lat, float $lon)
    {
        $this->lat = $lat;
        $this->lon = $lon;
    }
}
