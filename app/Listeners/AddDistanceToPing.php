<?php

namespace App\Listeners;

use App\DTOs\Geo;
use App\Helpers\GeoHelper;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Response;
use Illuminate\Queue\InteractsWithQueue;

class AddDistanceToPing
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $newPing = $event->ping;
        $lastPing = $newPing->previousPing();

        if ($lastPing) {

            $distance = GeoHelper::distance(
                $lastPing->geo,
                $newPing->geo
            );

            $newPing->distance_from_last_ping = $distance;
        }
    }
}
