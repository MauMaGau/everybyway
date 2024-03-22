<?php

namespace App\Console\Commands;

use App\Events\PingCreated;
use App\Models\Ping;
use App\Models\User;
use Illuminate\Console\Command;

class createFakePing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-fake-ping-on-local';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For test env only!!1';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ping = Ping::factory()->create(['user_id' => User::firstOrFail()->id]);
        PingCreated::dispatch($ping);
    }
}
