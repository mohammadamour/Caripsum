<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\CarFeatures;
use Illuminate\Database\Seeder;

class LargeCarSeeder extends Seeder
{
    /**
     * Seed the application with a larger realistic catalog for browsing.
     */
    public function run(): void
    {
        Car::factory()
            ->count(120)
            ->has(CarFeatures::factory(), 'features')
            ->create();

        $this->command->info('Successfully seeded 120 realistic cars with external images and feature data.');
    }
}
