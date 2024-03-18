<?php

namespace App\Listeners;

use App\Events\PingCreating;
use App\Events\PingSaving;
use Carbon\Carbon;
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

    public function handle(PingCreating $event): void
    {
        $previousPing = $event->ping->previousPing();

        if (!$previousPing) {
            // Start a new bimble
            $bimble = $event->ping->user->bimbles()->create(['started_at' => $event->ping->captured_at, 'ended_at' => $event->ping->captured_at]);
            $event->ping->bimble()->associate($bimble);
            return;
        }

        $timeIdle = $event->ping->captured_at->diffInMinutes($previousPing->captured_at);

        // If the user has been idle for too long, start a new bimble
        if ($timeIdle > env('BIMBLE_TIMEOUT')) {
            $bimble = $event->ping->user->bimbles()->create(['started_at' => $event->ping->captured_at, 'ended_at' => $event->ping->captured_at]);
            $event->ping->bimble()->associate($bimble);
            return;
        }

        // Add this to the current bimble the user is on
        if ($previousPing->bimble_id) {
            $event->ping->bimble()->associate($previousPing->bimble);
            $event->ping->bimble->ended_at = $event->ping->captured_at;
            $event->ping->bimble->save();
        }
    }
}
