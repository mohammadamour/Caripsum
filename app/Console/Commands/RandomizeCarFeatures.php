<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CarFeatures;

class RandomizeCarFeatures extends Command
{
    protected $signature = 'cars:randomize-features';
    protected $description = 'Randomize all car feature boolean values in the database';

    public function handle(): void
    {
        $columns = [
            'abs', 'air_conditioning', 'power_windows', 'power_door_locks',
            'cruise_control', 'bluetooth_connectivity', 'remote_start', 'gps_navigation', 'heated_seats',
            'climate_control', 'rear_parking_sensors', 'leather_seats',
        ];

        $count = 0;
        CarFeatures::all()->each(function ($features) use ($columns, &$count) {
            foreach ($columns as $col) {
                $features->{$col} = (bool) rand(0, 1);
            }
            $features->save();
            $count++;
        });

        $this->info("Randomized features for {$count} cars.");
    }
}
