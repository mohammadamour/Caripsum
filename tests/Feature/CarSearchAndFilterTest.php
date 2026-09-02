<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\CarFeatures;
use App\Models\CarType;
use App\Models\City;
use App\Models\FuelType;
use App\Models\Maker;
use App\Models\Model;
use App\Models\State;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CarSearchAndFilterTest extends TestCase
{
    use RefreshDatabase;

    private Maker $toyota;
    private Maker $bmw;
    private Model $camry;
    private Model $m3;
    private State $california;
    private City $losAngeles;
    private State $texas;
    private City $austin;
    private CarType $sedan;
    private CarType $suv;
    private FuelType $gasoline;
    private FuelType $electric;

    protected function setUp(): void
    {
        parent::setUp();

        $this->toyota = Maker::factory()->create(['name' => 'Toyota']);
        $this->camry = Model::factory()->create(['name' => 'Camry', 'maker_id' => $this->toyota->id]);

        $this->bmw = Maker::factory()->create(['name' => 'BMW']);
        $this->m3 = Model::factory()->create(['name' => 'M3', 'maker_id' => $this->bmw->id]);

        $this->california = State::factory()->create(['name' => 'California']);
        $this->losAngeles = City::factory()->create(['name' => 'Los Angeles', 'state_id' => $this->california->id]);

        $this->texas = State::factory()->create(['name' => 'Texas']);
        $this->austin = City::factory()->create(['name' => 'Austin', 'state_id' => $this->texas->id]);

        $this->sedan = CarType::factory()->create(['name' => 'Sedan']);
        $this->suv = CarType::factory()->create(['name' => 'SUV']);

        $this->gasoline = FuelType::factory()->create(['name' => 'Gasoline']);
        $this->electric = FuelType::factory()->create(['name' => 'Electric']);
    }

    /**
     * Test that only published cars appear in search results.
     */
    public function test_only_published_cars_appear_in_search(): void
    {
        $publishedCar = Car::factory()->create([
            'maker_id' => $this->toyota->id,
            'model_id' => $this->camry->id,
            'city_id' => $this->losAngeles->id,
            'published_at' => now()->subDay(),
        ]);

        $unpublishedCar = Car::factory()->create([
            'maker_id' => $this->bmw->id,
            'model_id' => $this->m3->id,
            'city_id' => $this->austin->id,
            'published_at' => null,
        ]);

        $futureCar = Car::factory()->create([
            'maker_id' => $this->bmw->id,
            'model_id' => $this->m3->id,
            'city_id' => $this->austin->id,
            'published_at' => now()->addDay(),
        ]);

        $response = $this->get(route('car.search'));

        $response->assertOk();
        $response->assertViewHas('cars', function ($cars) use ($publishedCar, $unpublishedCar, $futureCar) {
            $ids = $cars->pluck('id')->toArray();
            return in_array($publishedCar->id, $ids)
                && !in_array($unpublishedCar->id, $ids)
                && !in_array($futureCar->id, $ids);
        });
    }

    /**
     * Test searching by keyword across maker, model, city, and description.
     */
    public function test_search_by_keyword(): void
    {
        $car1 = Car::factory()->create([
            'maker_id' => $this->toyota->id,
            'model_id' => $this->camry->id,
            'city_id' => $this->losAngeles->id,
            'published_at' => now()->subDay(),
            'description' => 'Reliable family vehicle',
        ]);

        $car2 = Car::factory()->create([
            'maker_id' => $this->bmw->id,
            'model_id' => $this->m3->id,
            'city_id' => $this->austin->id,
            'published_at' => now()->subDay(),
            'description' => 'Fast sports car',
        ]);

        $response = $this->get(route('car.search', ['q' => 'Toyota']));
        $response->assertOk();
        $response->assertViewHas('cars', function ($cars) use ($car1, $car2) {
            $ids = $cars->pluck('id')->toArray();
            return in_array($car1->id, $ids) && !in_array($car2->id, $ids);
        });

        $responseDesc = $this->get(route('car.search', ['q' => 'sports car']));
        $responseDesc->assertOk();
        $responseDesc->assertViewHas('cars', function ($cars) use ($car1, $car2) {
            $ids = $cars->pluck('id')->toArray();
            return in_array($car2->id, $ids) && !in_array($car1->id, $ids);
        });
    }

    /**
     * Test filtering by maker and model.
     */
    public function test_filter_by_maker_and_model(): void
    {
        $toyotaCar = Car::factory()->create([
            'maker_id' => $this->toyota->id,
            'model_id' => $this->camry->id,
            'published_at' => now()->subDay(),
        ]);

        $bmwCar = Car::factory()->create([
            'maker_id' => $this->bmw->id,
            'model_id' => $this->m3->id,
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get(route('car.search', [
            'maker_id' => $this->toyota->id,
            'model_id' => $this->camry->id,
        ]));

        $response->assertOk();
        $response->assertViewHas('cars', function ($cars) use ($toyotaCar, $bmwCar) {
            $ids = $cars->pluck('id')->toArray();
            return in_array($toyotaCar->id, $ids) && !in_array($bmwCar->id, $ids);
        });
    }

    /**
     * Test filtering by state and city.
     */
    public function test_filter_by_state_and_city(): void
    {
        $californiaCar = Car::factory()->create([
            'city_id' => $this->losAngeles->id,
            'published_at' => now()->subDay(),
        ]);

        $texasCar = Car::factory()->create([
            'city_id' => $this->austin->id,
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get(route('car.search', ['state_id' => $this->california->id]));
        $response->assertOk();
        $response->assertViewHas('cars', function ($cars) use ($californiaCar, $texasCar) {
            $ids = $cars->pluck('id')->toArray();
            return in_array($californiaCar->id, $ids) && !in_array($texasCar->id, $ids);
        });
    }

    /**
     * Test filtering by price and year ranges.
     */
    public function test_filter_by_price_and_year_range(): void
    {
        $carLowPrice = Car::factory()->create([
            'price' => 10000,
            'year' => 2015,
            'published_at' => now()->subDay(),
        ]);

        $carMidPrice = Car::factory()->create([
            'price' => 25000,
            'year' => 2020,
            'published_at' => now()->subDay(),
        ]);

        $carHighPrice = Car::factory()->create([
            'price' => 60000,
            'year' => 2024,
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get(route('car.search', [
            'price_from' => 15000,
            'price_to' => 30000,
            'year_from' => 2018,
            'year_to' => 2022,
        ]));

        $response->assertOk();
        $response->assertViewHas('cars', function ($cars) use ($carLowPrice, $carMidPrice, $carHighPrice) {
            $ids = $cars->pluck('id')->toArray();
            return in_array($carMidPrice->id, $ids)
                && !in_array($carLowPrice->id, $ids)
                && !in_array($carHighPrice->id, $ids);
        });
    }

    /**
     * Test sorting by price ascending and descending.
     */
    public function test_sorting_search_results(): void
    {
        $carCheap = Car::factory()->create(['price' => 10000, 'published_at' => now()->subDay()]);
        $carExpensive = Car::factory()->create(['price' => 50000, 'published_at' => now()->subDay()]);

        $responseAsc = $this->get(route('car.search', ['sort' => 'price_asc']));
        $responseAsc->assertOk();
        $responseAsc->assertViewHas('cars', function ($cars) use ($carCheap, $carExpensive) {
            $first = $cars->first();
            return $first->id === $carCheap->id;
        });

        $responseDesc = $this->get(route('car.search', ['sort' => 'price_desc']));
        $responseDesc->assertOk();
        $responseDesc->assertViewHas('cars', function ($cars) use ($carCheap, $carExpensive) {
            $first = $cars->first();
            return $first->id === $carExpensive->id;
        });
    }
}
