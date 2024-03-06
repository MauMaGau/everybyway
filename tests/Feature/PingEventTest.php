<?php

namespace Tests\Feature;

use App\Models\HomeArea;
use App\Models\Ping;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PingEventTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_area_is_set_on_save(): void
    {
        $user = User::factory()->create();
        $homeArea = HomeArea::factory()->create(['lat' => 0, 'lon' => 0, 'user_id' => $user->id]);
        $ping = Ping::factory()->create(['lat' => 0, 'lon' => 0, 'user_id' => $user->id]);

        $this->assertTrue($ping->is_home_area);
    }

    public function test_home_area_is_not_set_on_save(): void
    {
        $user = User::factory()->create();
        $homeArea = HomeArea::factory()->create(['lat' => 50, 'lon' => 50, 'user_id' => $user->id]);
        $ping = Ping::factory()->create(['lat' => 0, 'lon' => 0, 'user_id' => $user->id]);

        $this->assertFalse($ping->is_home_area);
    }

    public function test_distance_is_set_on_save(): void
    {
        $ping1 = Ping::factory()->create(['lat' => 0, 'lon' => 0]);
        $ping2 = Ping::factory()->create(['lat' => 1, 'lon' => 0]);

        dd($ping2->distance());
        $this->assertFalse($ping->distance);
    }

    public function test_bimble_is_set(): void
    {

    }
}
