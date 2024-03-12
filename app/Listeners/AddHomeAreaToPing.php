<?php

namespace App\Listeners;

use App\Http\Controllers\PingController;
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
    public function handle(PingController $event): void
    {
        $event->ping->is_home_area = $event->ping->isPingInHomeArea();
    }
}
