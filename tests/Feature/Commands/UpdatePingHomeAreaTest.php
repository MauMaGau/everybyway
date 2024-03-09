<?php

namespace Tests\Feature\Commands;

use App\Models\HomeArea;
use App\Models\Ping;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdatePingHomeAreaTest extends TestCase
{
    use RefreshDatabase;

    public function test_ping_home_area_is_updated(): void
    {
        $user = User::factory()
            ->has(HomeArea::factory()->state(function () {return ['lat' => 0, 'lon' => 0];}))
            ->has(Ping::factory()->state(function () {return ['lat' => 1, 'lon' => 1];}))
            ->create();

        $ping = $user->pings->first();

        $this->assertFalse($ping->is_home_area);

        $homeArea = $user->homeAreas->first();
        $homeArea->lat = 1;
        $homeArea->lon = 1;
        $homeArea->save();

        $this->artisan('app:update-ping-home-area')->assertExitCode(0);

        $ping->refresh();

        $this->assertTrue($ping->is_home_area);
    }
}
