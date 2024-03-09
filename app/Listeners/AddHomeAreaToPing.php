<?php

namespace App\Listeners;

use App\DTOs\Geo;
use App\Events\PingSaving;
use App\Helpers\GeoHelper;
use App\Models\HomeArea;
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
    public function handle(PingSaving $event): void
    {
        $event->ping->is_home_area = $event->ping->isPingInHomeArea();
    }
}
