<?php

namespace App\Listeners;

use App\DTOs\Geo;
use App\Events\PingSaving;
use App\Helpers\GeoHelper;
use App\Models\HomeArea;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class AddHomeAreaToBimble
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
    public function handle(PingSaving $event): void
    {
        if ($event->ping->user->HomeAreas->isEmpty()) {
            return;
        }

        $event->ping->user->homeAreas->each(function(HomeArea $homeArea) use ($event) {
            if (GeoHelper::distance(
                    $homeArea->geo,
                    $event->ping->geo
                ) < env('HOME_RADIUS')) {
                $event->ping->is_home_area = true;
            }
        });
    }
}
