<?php

namespace App\Events;

use App\Models\Ping;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PingCreating
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Ping $ping;

    /**
     * Create a new event instance.
     */
    public function __construct(Ping $ping)
    {
        $this->ping = $ping;
    }

}
