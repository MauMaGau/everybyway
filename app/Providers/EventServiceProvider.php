<?php

namespace App\Providers;

use App\Events\PingCreating;
use App\Events\PingSaving;
use App\Listeners\AddDistanceToPing;
use App\Listeners\AddHomeAreaToPing;
use App\Listeners\AddPingToBimble;
use App\Listeners\AddCapturedAtToPing;
use App\Listeners\UpdateMapWithPing;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        PingSaving::class => [
            AddCapturedAtToPing::class,
            AddPingToBimble::class,
            AddHomeAreaToPing::class,
            AddDistanceToPing::class,
            UpdateMapWithPing::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
