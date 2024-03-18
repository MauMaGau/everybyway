<?php

namespace App\Console\Commands;

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
    protected $signature = 'app:create-fake-ping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Ping::factory()->create(['user_id' => User::firstOrFail()->id]);
    }
}
