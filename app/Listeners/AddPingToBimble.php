<?php

namespace App\Listeners;

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

    public function handle(PingSaving $event): void
    {
        $previousPing = $event->ping->previousPing();

        // If the user has been idle for too long, start a new bimble
        if ($previousPing && Carbon::createFromFormat('Y-m-d H:i:s', $event->ping->captured_at)->diffInMinutes($previousPing->captured_at) > env('BIMBLE_TIMEOUT')) {
            $bimble = $event->ping->user->bimbles()->create(['started_at' => $event->ping->captured_at, 'ended_at' => $event->ping->captured_at]);
            $event->ping->bimble()->associate($bimble);
            return;
        }

        // Add this to the current bimble the user is on
        if ($previousPing && $previousPing->bimble_id) {
            $event->ping->bimble()->associate($previousPing->bimble);
            $event->ping->bimble->ended_at = $event->ping->captured_at;
            $event->ping->bimble->save();
            return;
        }

        // Start a new bimble
        $bimble = $event->ping->user->bimbles()->create(['started_at' => $event->ping->captured_at, 'ended_at' => $event->ping->captured_at]);
        $event->ping->bimble()->associate($bimble);
    }
}
