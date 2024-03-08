<?php

namespace Tests\Feature;

use App\Models\Ping;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BimbleTest extends TestCase
{
    public function test_start_and_end_date_is_set(): void
    {
        $user1 = User::factory()->create();
        $ping1 = Ping::factory()->create(['lat' => 0, 'lon' => 0, 'user_id' => $user1->id]);
        $ping2 = Ping::factory()->create(['lat' => 1, 'lon' => 0, 'user_id' => $user1->id]);

        $bimble = $ping1->refresh()->bimble;

        $this->assertNotEmpty($bimble);
        $this->assertEquals($ping1->captured_at, $ping2->captured_at);
    }
}
