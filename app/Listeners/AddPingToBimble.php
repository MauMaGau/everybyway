<?php

namespace App\Listeners;

use App\Events\PingSaving;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddPingToBimble
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * If the
     */
    public function handle(PingSaving $event): void
    {
        $previousPing = $event->ping->previousPing();

        // Are we still on the same bimble?
        if ($event->ping->distance_from_last_ping > env('BIMBLE_TIMEOUT')) {
            return;
        }

        if ($event->ping->previousPing() && $event->ping->previousPing()->bimble_id) {
            $event->ping->bimble()->associate($event->ping->previousPing()->bimble);
            return;
        }

        $bimble = $event->ping->user->bimbles()->create();
        $event->ping->bimble()->associate($bimble);
    }
}
