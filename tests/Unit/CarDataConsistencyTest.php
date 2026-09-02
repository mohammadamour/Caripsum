<?php

namespace Tests\Unit;

use App\Models\Car;
use App\Models\CarFeatures;
use PHPUnit\Framework\TestCase;

class CarDataConsistencyTest extends TestCase
{
    /**
     * Test that Car model fillable attributes match canonical schema and exclude state_id.
     */
    public function test_car_fillable_attributes_match_expected_schema(): void
    {
        $car = new Car();
        $fillable = $car->getFillable();

        $this->assertNotContains('state_id', $fillable, 'Car schema must not contain state_id');
        $this->assertContains('city_id', $fillable);
        $this->assertContains('car_type_id', $fillable);
        $this->assertContains('fuel_type_id', $fillable);
        $this->assertContains('maker_id', $fillable);
        $this->assertContains('model_id', $fillable);
        $this->assertContains('year', $fillable);
        $this->assertContains('price', $fillable);
        $this->assertContains('vin', $fillable);
        $this->assertContains('mileage', $fillable);
        $this->assertContains('address', $fillable);
        $this->assertContains('phone', $fillable);
        $this->assertContains('description', $fillable);
        $this->assertContains('published_at', $fillable);
    }

    /**
     * Test that CarFeatures model uses canonical snake_case attribute naming.
     */
    public function test_car_features_fillable_attributes_use_canonical_naming(): void
    {
        $features = new CarFeatures();
        $fillable = $features->getFillable();

        $this->assertContains('power_door_locks', $fillable);
        $this->assertContains('bluetooth_connectivity', $fillable);
        $this->assertContains('abs', $fillable);
        $this->assertContains('air_conditioning', $fillable);
        $this->assertContains('power_windows', $fillable);
        $this->assertContains('cruise_control', $fillable);
        $this->assertContains('remote_start', $fillable);
        $this->assertContains('gps_navigation', $fillable);
        $this->assertContains('heated_seats', $fillable);
        $this->assertContains('climate_control', $fillable);
        $this->assertContains('rear_parking_sensors', $fillable);
        $this->assertContains('leather_seats', $fillable);

        // Ensure legacy drifted column names are absent
        $this->assertNotContains('power_doors_locks', $fillable);
        $this->assertNotContains('bluetooth-connectivity', $fillable);
    }
}
