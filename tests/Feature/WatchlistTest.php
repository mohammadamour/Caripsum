<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WatchlistTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Car $car;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->car = Car::factory()->create(['published_at' => now()->subDay()]);
    }

    /**
     * Test authenticated user can toggle a car into their watchlist.
     */
    public function test_authenticated_user_can_add_and_remove_car_from_watchlist(): void
    {
        // 1. Add car to watchlist
        $responseAdd = $this->actingAs($this->user)
            ->postJson(route('car.watchlist.toggle', $this->car));

        $responseAdd->assertOk();
        $responseAdd->assertJson(['added' => true]);

        $this->assertDatabaseHas('car_favorite', [
            'user_id' => $this->user->id,
            'car_id' => $this->car->id,
        ]);

        // 2. Remove car from watchlist (toggle again)
        $responseRemove = $this->actingAs($this->user)
            ->postJson(route('car.watchlist.toggle', $this->car));

        $responseRemove->assertOk();
        $responseRemove->assertJson(['added' => false]);

        $this->assertDatabaseMissing('car_favorite', [
            'user_id' => $this->user->id,
            'car_id' => $this->car->id,
        ]);
    }

    /**
     * Test authenticated user can view their watchlist page.
     */
    public function test_authenticated_user_can_view_saved_cars_in_watchlist(): void
    {
        $this->user->favouriteCars()->attach($this->car->id);

        $response = $this->actingAs($this->user)->get(route('car.watchlist'));

        $response->assertOk();
        $response->assertViewIs('car.watchlist');
        $response->assertViewHas('cars', function ($cars) {
            return $cars->contains('id', $this->car->id);
        });
    }

    /**
     * Test guest cannot access watchlist page or toggle watchlist.
     */
    public function test_guest_cannot_interact_with_watchlist(): void
    {
        $responseGet = $this->get(route('car.watchlist'));
        $responseGet->assertRedirect(route('login'));

        $responsePost = $this->post(route('car.watchlist.toggle', $this->car));
        $responsePost->assertRedirect(route('login'));
    }
}
