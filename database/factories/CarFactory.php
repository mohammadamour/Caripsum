<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\CarType;
use App\Models\City;
use App\Models\FuelType;
use App\Models\Maker;
use App\Models\Model;
use App\Models\User;
use App\Models\CarImages;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'maker_id' => Maker::query()->inRandomOrder()->value('id')
                ?? Maker::factory()->create()->id,
            'model_id' => function(array $attributes) {
               $modelId = Model::where('maker_id', $attributes['maker_id'])
                    ->inRandomOrder()->value('id');

               if ($modelId) {
                   return $modelId;
               }

               return Model::factory()->create([
                   'maker_id' => $attributes['maker_id'],
               ])->id;
            },

            'year' => fake()->year(),
            'price' => ((int)fake()->randomFloat(2, 5, 100) * 1000),
            'vin' => strtoupper(Str::random(17)),
            'mileage' => ((int)fake()->randomFloat(2, 5, 500) * 1000),
            'car_type_id' => CarType::query()->inRandomOrder()->value('id')
                ?? CarType::factory()->create()->id,
            'fuel_type_id' => FuelType::query()->inRandomOrder()->value('id')
                ?? FuelType::factory()->create()->id,
            'user_id' => User::query()->inRandomOrder()->value('id')
                ?? User::factory()->create()->id,
            'city_id' => City::query()->inRandomOrder()->value('id')
                ?? City::factory()->create()->id,
            'address' => fake()->address(),
            'phone' =>function (array $attributes) {
                $user = User::find($attributes['user_id']);
                return $user ? $user->phone : fake()->phoneNumber();
            },
            'description' =>fake()->text(20000),
            'published_at' =>fake()->optional(0.9)
            ->dateTimeBetween('-1 month', '+1 day')

        ];
    }

    /**
     * Configure the factory to automatically create images for cars.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Car $car) {
            // Only create images if the car doesn't already have images
            // This prevents duplicate images when using ->has() in seeders
            if ($car->images()->count() === 0) {
                // Create 3-5 random images for each car
                $imageCount = fake()->numberBetween(3, 5);
                
                for ($i = 1; $i <= $imageCount; $i++) {
                    CarImages::factory()->create([
                        'car_id' => $car->id,
                        'position' => $i,
                    ]);
                }
            }
        });
    }
}
