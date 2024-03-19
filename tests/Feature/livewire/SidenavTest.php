<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Layout\SidenavAuth;
use App\Models\Bimble;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class SidenavTest extends TestCase
{
    public function test_the_application_returns_a_successful_response(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)->test(SidenavAuth::class)
            ->assertStatus(200);
    }
}
