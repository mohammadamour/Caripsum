<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\CarFeatures;
use App\Models\CarImages;
use App\Models\CarType;
use App\Models\City;
use App\Models\FuelType;
use App\Models\Maker;
use App\Models\Model;
use App\Models\State;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CarManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Maker $maker;
    private Model $model;
    private CarType $carType;
    private FuelType $fuelType;
    private City $city;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $state = State::factory()->create(['name' => 'California']);
        $this->city = City::factory()->create([
            'name' => 'San Francisco',
            'state_id' => $state->id,
        ]);

        $this->maker = Maker::factory()->create(['name' => 'Toyota']);
        $this->model = Model::factory()->create([
            'name' => 'Camry',
            'maker_id' => $this->maker->id,
        ]);

        $this->carType = CarType::factory()->create(['name' => 'Sedan']);
        $this->fuelType = FuelType::factory()->create(['name' => 'Gasoline']);
    }

    /**
     * Test authenticated user can access the create car page.
     */
    public function test_authenticated_user_can_view_create_car_page(): void
    {
        $response = $this->actingAs($this->user)->get(route('car.create'));

        $response->assertOk();
        $response->assertViewIs('car.create');
        $response->assertViewHasAll(['makers', 'carTypes', 'fuelTypes', 'cities']);
    }

    /**
     * Test guest user is redirected to login when accessing create car page.
     */
    public function test_guest_is_redirected_to_login_from_create_car_page(): void
    {
        $response = $this->get(route('car.create'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test happy path: successfully store a new car with features and uploaded images.
     */
    public function test_authenticated_user_can_create_car_with_features_and_images(): void
    {
        Storage::fake('public');

        $image1 = UploadedFile::fake()->image('front.jpg', 800, 600)->size(500);
        $image2 = UploadedFile::fake()->image('side.png', 800, 600)->size(500);

        $payload = [
            'maker_id' => $this->maker->id,
            'model_id' => $this->model->id,
            'year' => 2022,
            'car_type_id' => $this->carType->id,
            'price' => 25000,
            'vin' => '1HGCR2F83HA123456',
            'mileage' => 30000,
            'fuel_type_id' => $this->fuelType->id,
            'city_id' => $this->city->id,
            'address' => '123 Market St',
            'phone' => '+1-555-0199',
            'description' => 'Clean title, single owner vehicle in excellent condition.',
            'published' => '1',
            'abs' => 'on',
            'air_conditioning' => 'on',
            'bluetooth_connectivity' => 'on',
            'power_door_locks' => 'on',
            'images' => [$image1, $image2],
        ];

        $response = $this->actingAs($this->user)->post(route('car.store'), $payload);

        $response->assertRedirect(route('car.index'));
        $response->assertSessionHas('success', 'Car created successfully!');

        $this->assertDatabaseHas('cars', [
            'user_id' => $this->user->id,
            'maker_id' => $this->maker->id,
            'model_id' => $this->model->id,
            'year' => 2022,
            'price' => 25000,
            'vin' => '1HGCR2F83HA123456',
            'mileage' => 30000,
            'city_id' => $this->city->id,
        ]);

        $car = Car::where('vin', '1HGCR2F83HA123456')->first();
        $this->assertNotNull($car);
        $this->assertNotNull($car->published_at);

        // Assert car features
        $this->assertDatabaseHas('car_features', [
            'car_id' => $car->id,
            'abs' => true,
            'air_conditioning' => true,
            'bluetooth_connectivity' => true,
            'power_door_locks' => true,
            'heated_seats' => false,
        ]);

        // Assert uploaded images recorded and stored
        $this->assertDatabaseCount('car_images', 2);
        $this->assertDatabaseHas('car_images', [
            'car_id' => $car->id,
            'position' => 1,
        ]);

        $storedImages = CarImages::where('car_id', $car->id)->get();
        foreach ($storedImages as $img) {
            $storageRelativePath = str_replace('/storage/', '', $img->image_path);
            Storage::disk('public')->assertExists($storageRelativePath);
        }
    }

    /**
     * Test validation fails when required fields are missing.
     */
    public function test_car_creation_requires_mandatory_fields(): void
    {
        $response = $this->actingAs($this->user)->post(route('car.store'), []);

        $response->assertSessionHasErrors([
            'maker_id',
            'model_id',
            'year',
            'car_type_id',
            'price',
            'vin',
            'mileage',
            'fuel_type_id',
            'city_id',
            'address',
            'phone',
        ]);
    }

    /**
     * Test validation edge cases: invalid year range, negative price and mileage.
     */
    public function test_car_creation_validates_numeric_and_year_ranges(): void
    {
        $payload = [
            'maker_id' => $this->maker->id,
            'model_id' => $this->model->id,
            'year' => 1980, // Below min 1990
            'car_type_id' => $this->carType->id,
            'price' => -500, // Negative price
            'vin' => '1HGCR2F83HA123456',
            'mileage' => -100, // Negative mileage
            'fuel_type_id' => $this->fuelType->id,
            'city_id' => $this->city->id,
            'address' => '123 Market St',
            'phone' => '+1-555-0199',
        ];

        $response = $this->actingAs($this->user)->post(route('car.store'), $payload);

        $response->assertSessionHasErrors(['year', 'price', 'mileage']);
    }

    /**
     * Test validation rejects invalid image file formats and oversized files.
     */
    public function test_car_creation_rejects_invalid_and_oversized_images(): void
    {
        Storage::fake('public');

        $textFile = UploadedFile::fake()->create('document.pdf', 500, 'application/pdf');
        $oversizedImage = UploadedFile::fake()->image('huge.jpg')->size(3000); // 3000KB > 2048KB limit

        $payload = [
            'maker_id' => $this->maker->id,
            'model_id' => $this->model->id,
            'year' => 2020,
            'car_type_id' => $this->carType->id,
            'price' => 15000,
            'vin' => '1HGCR2F83HA123456',
            'mileage' => 50000,
            'fuel_type_id' => $this->fuelType->id,
            'city_id' => $this->city->id,
            'address' => '123 Market St',
            'phone' => '+1-555-0199',
            'images' => [$textFile, $oversizedImage],
        ];

        $response = $this->actingAs($this->user)->post(route('car.store'), $payload);

        $response->assertSessionHasErrors(['images.0', 'images.1']);
    }

    /**
     * Test happy path: owner can view edit page for their car.
     */
    public function test_owner_can_view_edit_page(): void
    {
        $car = Car::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get(route('car.edit', $car));

        $response->assertOk();
        $response->assertViewIs('car.edit');
        $response->assertViewHas('car', $car);
    }

    /**
     * Test authorization: non-owner receives 403 Forbidden when accessing edit page.
     */
    public function test_non_owner_cannot_view_edit_page(): void
    {
        $otherUser = User::factory()->create();
        $car = Car::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)->get(route('car.edit', $car));

        $response->assertForbidden();
    }

    /**
     * Test happy path: owner can update car details and append images.
     */
    public function test_owner_can_update_car_details_and_append_images(): void
    {
        Storage::fake('public');

        $car = Car::factory()->create([
            'user_id' => $this->user->id,
            'maker_id' => $this->maker->id,
            'model_id' => $this->model->id,
            'car_type_id' => $this->carType->id,
            'fuel_type_id' => $this->fuelType->id,
            'city_id' => $this->city->id,
            'price' => 20000,
            'mileage' => 45000,
        ]);

        CarFeatures::factory()->create([
            'car_id' => $car->id,
            'leather_seats' => false,
            'heated_seats' => false,
        ]);

        $initialMaxPosition = $car->images()->max('position') ?? 0;
        $initialImageCount = $car->images()->count();

        $newImage = UploadedFile::fake()->image('additional.jpg', 800, 600)->size(400);

        $updatePayload = [
            'maker_id' => $this->maker->id,
            'model_id' => $this->model->id,
            'year' => 2021,
            'car_type_id' => $this->carType->id,
            'price' => 22000,
            'vin' => '1HGCR2F83HA999999',
            'mileage' => 46000,
            'fuel_type_id' => $this->fuelType->id,
            'city_id' => $this->city->id,
            'address' => '456 Updated Blvd',
            'phone' => '+1-555-0188',
            'description' => 'Updated car description.',
            'published' => '1',
            'leather_seats' => 'on',
            'heated_seats' => 'on',
            'images' => [$newImage],
        ];

        $response = $this->actingAs($this->user)->put(route('car.update', $car), $updatePayload);

        $response->assertRedirect(route('car.index'));
        $response->assertSessionHas('success', 'Car updated successfully!');

        $this->assertDatabaseHas('cars', [
            'id' => $car->id,
            'price' => 22000,
            'mileage' => 46000,
            'vin' => '1HGCR2F83HA999999',
        ]);

        $this->assertDatabaseHas('car_features', [
            'car_id' => $car->id,
            'leather_seats' => true,
            'heated_seats' => true,
        ]);

        // Verify image was appended with incremented position
        $this->assertDatabaseHas('car_images', [
            'car_id' => $car->id,
            'position' => $initialMaxPosition + 1,
        ]);
        $this->assertEquals($initialImageCount + 1, $car->images()->count());
    }

    /**
     * Test authorization: non-owner cannot update someone else's car.
     */
    public function test_non_owner_cannot_update_car(): void
    {
        $otherUser = User::factory()->create();
        $car = Car::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)->put(route('car.update', $car), [
            'maker_id' => $this->maker->id,
            'model_id' => $this->model->id,
            'year' => 2020,
            'car_type_id' => $this->carType->id,
            'price' => 18000,
            'vin' => '1HGCR2F83HA123456',
            'mileage' => 30000,
            'fuel_type_id' => $this->fuelType->id,
            'city_id' => $this->city->id,
            'address' => '123 Market St',
            'phone' => '+1-555-0199',
        ]);

        $response->assertForbidden();
    }

    /**
     * Test happy path: owner can soft-delete their car.
     */
    public function test_owner_can_delete_car(): void
    {
        $car = Car::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->delete(route('car.destroy', $car));

        $response->assertRedirect(route('car.index'));
        $response->assertSessionHas('success', 'Car deleted successfully!');

        $this->assertSoftDeleted('cars', [
            'id' => $car->id,
        ]);
    }

    /**
     * Test authorization: non-owner cannot delete someone else's car.
     */
    public function test_non_owner_cannot_delete_car(): void
    {
        $otherUser = User::factory()->create();
        $car = Car::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)->delete(route('car.destroy', $car));

        $response->assertForbidden();
        $this->assertNotSoftDeleted('cars', ['id' => $car->id]);
    }

    /**
     * Test authenticated user can view their cars list (my cars dashboard).
     */
    public function test_authenticated_user_can_view_my_cars_dashboard(): void
    {
        $myCar = Car::factory()->create([
            'user_id' => $this->user->id,
            'maker_id' => $this->maker->id,
            'model_id' => $this->model->id,
            'year' => 2022,
        ]);
        $otherCar = Car::factory()->create();

        $response = $this->actingAs($this->user)->get(route('car.index'));

        $response->assertOk();
        $response->assertViewIs('car.index');
        $response->assertSee($this->maker->name);
        $response->assertSee($this->model->name);
    }

    /**
     * Test public can view published car detail page.
     */
    public function test_public_user_can_view_car_detail_page(): void
    {
        $car = Car::factory()->create([
            'maker_id' => $this->maker->id,
            'model_id' => $this->model->id,
            'year' => 2023,
            'published_at' => now()->subDay(),
        ]);

        CarFeatures::factory()->create(['car_id' => $car->id]);

        $response = $this->get(route('car.show', $car));

        $response->assertOk();
        $response->assertViewIs('car.show');
        $response->assertSee($this->maker->name);
        $response->assertSee($this->model->name);
    }
}
