<?php

namespace App\Listeners;

use App\Events\PingCreating;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddCapturedAtToPing
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
    public function handle(PingCreating $event): void
    {
        if ($event->ping->captured_at) {
            return;
        }

        $event->ping->captured_at = Carbon::now()->toDateTimeString();
    }
}
