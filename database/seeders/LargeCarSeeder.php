<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\CarFeatures;
use Illuminate\Database\Seeder;

class LargeCarSeeder extends Seeder
{
    /**
     * Seed the application with 50 new cars for better search demonstration.
     */
    public function run(): void
    {
        // Generate 50 cars, each with its own features and images (handled by factory configure)
        Car::factory()
            ->count(500)
            ->has(CarFeatures::factory(), 'features')
            ->create();

        $this->command->info('Successfully seeded 500 new cars with random images and features!');
    }
}
