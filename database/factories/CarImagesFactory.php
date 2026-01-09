<?php

namespace Database\Factories;

use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CarImages>
 */
class CarImagesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'image_path' => function (array $attributes) {
                $carId = $attributes['car_id'] ?? 0;
                
                if (isset($attributes['position'])) {
                    $position = $attributes['position'];
                } else {
                    $car = Car::find($carId);
                    $position = ($car?->images()->count() ?? 0) + 1;
                }
                
                return "https://picsum.photos/seed/{$carId}-{$position}/640/480";
            },
            'position' => function (array $attributes) {
                return Car::find($attributes['car_id'])->images()->count() + 1;
            }
        ]; 
    }
}
