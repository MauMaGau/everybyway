<?php

namespace Tests\Feature\Livewire;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Livewire\Layout\Sidenav;
use App\Models\Bimble;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class SidenavTest extends TestCase
{
    public function test_the_application_returns_a_successful_response(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)->test(Sidenav::class)
            ->assertStatus(200);
    }

    public function test_the_months_property_is_populated(): void
    {
        $user = User::factory()->create();
        $bimble1 = Bimble::factory()->create(['user_id' => $user->id]);

        Livewire::actingAs($user)->test(Sidenav::class)
            ->assertViewHas('months', function ($months) {
                return is_array($months) && count($months) === 1;
            });
    }
}
