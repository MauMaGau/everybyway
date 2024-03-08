<?php

namespace Tests\Feature\Api;

use App\Models\Ping;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PingTest extends TestCase
{
    use RefreshDatabase;

    public function test_ping_post_ok(): void
    {
        $user = User::factory()->create();

        Ping::factory()->create(['user_id' => $user->id]);

        $response = $this->post(route('api.ping', ['lat'=> 0, 'lon' => 0, 'timestamp' => Carbon::now()->timestamp]));

        $response->assertStatus(200);
    }

    public function test_ping_post_missing_data(): void
    {
        User::factory()->create();
        $response = $this->post(route('api.ping'));

        $response->assertStatus(422);
    }
}
