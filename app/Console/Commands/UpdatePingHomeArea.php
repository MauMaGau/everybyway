<?php

namespace App\Console\Commands;

use App\Helpers\GeoHelper;
use App\Models\HomeArea;
use App\Models\Ping;
use App\Models\User;
use Illuminate\Console\Command;

class UpdatePingHomeArea extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-ping-home-area';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Re-check everyones pings to see if the home area has changed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();

        $users->each(function (User $user) {
            $user->pings->each(function (Ping $ping) {
                $isHomeArea = $ping->isPingInHomeArea();

                if ($isHomeArea !== $ping->is_home_area) {
                    $ping->is_home_area = $isHomeArea;
                    $ping->save();
                }
            });
        });
    }
}
