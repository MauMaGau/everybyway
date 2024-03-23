<?php

namespace Tests\Feature;

use App\Models\HomeArea;
use App\Models\Ping;
use App\Models\User;
use Carbon\Carbon;
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
        $user = User::factory()->create();
        $ping1 = Ping::factory()->create(['lat' => 0, 'lon' => 0, 'user_id' => $user->id]);
        $ping2 = Ping::factory()->create(['lat' => 1, 'lon' => 0, 'user_id' => $user->id]);

        $this->assertNull($ping1->distance_from_last_ping);
        $this->assertNotNull($ping2->distance_from_last_ping);
    }

    public function test_bimble_is_set_on_ping_if_part_of_journey(): void
    {
        $user = User::factory()->create();
        $ping1 = Ping::factory()->create(['lat' => 0, 'lon' => 0, 'user_id' => $user->id]);
        $ping2 = Ping::factory()->create(['lat' => 1, 'lon' => 0, 'user_id' => $user->id]);

        $this->assertNotEmpty($ping1->bimble);
        $this->assertNotEmpty($ping2->bimble);
        $this->assertTrue($ping1->bimble->is($ping2->bimble));
    }

    public function test_new_bimble_is_created_if_previous_ping_too_long_ago(): void
    {
        $user = User::factory()->create();
        $ping1 = Ping::factory()->create(['lat' => 0, 'lon' => 0, 'user_id' => $user->id, 'captured_at' => Carbon::now()->subMinutes(env('BIMBLE_TIMEOUT') + 1)]);
        $ping2 = Ping::factory()->create(['lat' => 1, 'lon' => 0, 'user_id' => $user->id]);

        $this->assertNotEmpty($ping1->bimble);
        $this->assertNotEmpty($ping2->bimble);
        $this->assertFalse($ping1->bimble->is($ping2->bimble));
    }
}
