<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\User;
use App\Models\CarType;
use App\Models\FuelType;
use App\Models\State;
use App\Models\City;
use App\Models\Maker;
use App\Models\Model;
use App\Models\CarImages;
use App\Models\CarFeatures;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'fname' => 'fname test',
            'lname' => 'lname test',
            'email' => 'test@example.com',
        ]);

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


    foreach ($makers as $maker => $models) {
        Maker::factory()
        ->state(['name' => $maker])
        ->has(
            Model::factory()
            ->count(count($models))
            ->sequence(...array_map(fn($model) => ['name' => $model], $models))
        )
        ->create();
    }

    User::factory()
    ->count(3)
    ->create();

    User::factory()
    ->count(2)
    ->has(
        Car::factory()
        ->count(10)
        ->has(
            CarImages::factory()
            ->count(5)
            ->sequence(
                ['position' => 1],
                ['position' => 2],
                ['position' => 3],
                ['position' => 4],
                ['position' => 5]
            ),
            'images'
        )
        ->has(CarFeatures::factory(), 'features'),
        'favouriteCars'
    )
    ->create();




}
}
