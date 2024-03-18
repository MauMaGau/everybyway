<?php

namespace App\Listeners;

use App\Events\PingCreating;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class AddHomeAreaToPing
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
        $event->ping->is_home_area = $event->ping->isPingInHomeArea();
    }
}
