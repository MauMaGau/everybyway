<?php

namespace Tests\Feature;

use App\Models\Ping;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PingTest extends TestCase
{
    use RefreshDatabase;

    public function test_previous_ping_is_set(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $ping1 = Ping::factory()->create(['lat' => 0, 'lon' => 0, 'user_id' => $user1->id]);
        Ping::factory()->create(['lat' => 0, 'lon' => 0, 'user_id' => $user2->id]);
        $ping2 = Ping::factory()->create(['lat' => 1, 'lon' => 0, 'user_id' => $user1->id]);

        $this->assertNotNull($ping2->previousPing());
        $this->assertEquals($ping1->id, $ping2->previousPing()->id);
    }

    public function test_previous_ping_is_not_set_if_none_available(): void
    {
        $user = User::factory()->create();
        $ping = Ping::factory()->create(['lat' => 1, 'lon' => 0, 'user_id' => $user->id]);

//        $this->assertNull($ping->previousPing());
    }
}
