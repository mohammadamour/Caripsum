<?php

use App\Models\Car;
use App\Models\CarFeatures;

it('uses the database schema expected by the app for car fields', function () {
    expect(Car::getFillable())
        ->not->toContain('state_id')
        ->toContain('city_id')
        ->toContain('car_type_id')
        ->toContain('fuel_type_id');
});

it('uses one canonical naming strategy for feature columns', function () {
    expect(CarFeatures::getFillable())
        ->toContain('power_door_locks')
        ->toContain('bluetooth_connectivity')
        ->not->toContain('power_doors_locks')
        ->not->toContain('bluetooth-connectivity');
});
