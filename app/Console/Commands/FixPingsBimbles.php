<?php

namespace App\Console\Commands;

use App\Models\Bimble;
use App\Models\Ping;
use App\Models\User;
use Illuminate\Console\Command;

class FixPingsBimbles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-pings-bimbles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ensure each ping has the correct bimble associated';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        User::all()->each(function (User $user) {
            $user->bimbles()->each(function (Bimble $bimble) use ($user) {
                Ping::where('user_id', $user->id)
                    ->where('bimble_id', '!=', $bimble->id)
                    ->whereDate('captured_at', '>=', $bimble->started_at)
                    ->whereDate('captured_at', '<=', $bimble->ended_at)
                    ->update(['bimble_id' => $bimble->id]);
            });
        });
    }
}
