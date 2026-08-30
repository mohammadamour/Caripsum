<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\CarFeatures;
use App\Models\CarType;
use App\Models\City;
use App\Models\FuelType;
use App\Models\Maker;
use App\Models\Model;
use App\Models\State;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with realistic marketplace data.
     */
    public function run(): void
    {
        User::factory()->create([
            'fname' => 'Demo',
            'lname' => 'Seller',
            'email' => 'demo@example.com',
            'phone' => '+1-555-0100',
        ]);

        User::factory()->count(12)->create();

        CarType::factory()
            ->sequence(
                ['name' => 'Sedan'],
                ['name' => 'Hatchback'],
                ['name' => 'SUV'],
                ['name' => 'Pickup Truck'],
                ['name' => 'Van'],
                ['name' => 'Minivan'],
                ['name' => 'Coupe'],
                ['name' => 'Crossover'],
                ['name' => 'Sports Car'],
                ['name' => 'Jeep'],
                ['name' => 'Wagon'],
            )
            ->count(11)
            ->create();

        FuelType::factory()
            ->sequence(
                ['name' => 'Gasoline'],
                ['name' => 'Diesel'],
                ['name' => 'Electric'],
                ['name' => 'Hybrid'],
                ['name' => 'Plug-in Hybrid'],
            )
            ->count(5)
            ->create();

        $states = [
            'California' => ['Los Angeles', 'San Francisco', 'San Diego', 'Sacramento'],
            'Texas' => ['Houston', 'Dallas', 'Austin', 'San Antonio'],
            'New York' => ['New York City', 'Buffalo', 'Rochester'],
            'Florida' => ['Miami', 'Orlando', 'Tampa', 'Jacksonville'],
            'Illinois' => ['Chicago', 'Aurora', 'Naperville'],
            'Pennsylvania' => ['Philadelphia', 'Pittsburgh', 'Allentown'],
            'Washington' => ['Seattle', 'Spokane', 'Tacoma'],
        ];

        foreach ($states as $stateName => $cities) {
            $state = State::create(['name' => $stateName]);

            foreach ($cities as $cityName) {
                City::create([
                    'name' => $cityName,
                    'state_id' => $state->id,
                ]);
            }
        }

        $makers = [
            'Toyota' => ['Camry', 'Corolla', 'Rav4', 'Highlander'],
            'Ford' => ['F-150', 'Mustang', 'Explorer', 'Bronco'],
            'Honda' => ['Civic', 'Accord', 'CR-V', 'HR-V'],
            'Chevrolet' => ['Silverado', 'Equinox', 'Traverse', 'Blazer'],
            'Nissan' => ['Altima', 'Sentra', 'Versa', 'Rogue'],
            'Lexus' => ['ES', 'RX', 'NX', 'GX'],
            'BMW' => ['3 Series', '5 Series', 'X5', 'X3'],
            'Mercedes' => ['C-Class', 'E-Class', 'GLE', 'GLC'],
        ];

        foreach ($makers as $makerName => $models) {
            $maker = Maker::create(['name' => $makerName]);

            foreach ($models as $modelName) {
                Model::create([
                    'name' => $modelName,
                    'maker_id' => $maker->id,
                ]);
            }
        }

        Car::factory()
            ->count(80)
            ->has(CarFeatures::factory(), 'features')
            ->create();
    }
}
