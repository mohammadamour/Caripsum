<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('car_features')) {
            return;
        }

        $renames = [
            'power_doors_locks' => 'power_door_locks',
            'bluetooth-connectivity' => 'bluetooth_connectivity',
        ];

        foreach ($renames as $from => $to) {
            if (Schema::hasColumn('car_features', $from)) {
                DB::statement("ALTER TABLE \"car_features\" RENAME COLUMN \"{$from}\" TO \"{$to}\"");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('car_features')) {
            return;
        }

        $renames = [
            'power_door_locks' => 'power_doors_locks',
            'bluetooth_connectivity' => 'bluetooth-connectivity',
        ];

        foreach ($renames as $from => $to) {
            if (Schema::hasColumn('car_features', $from)) {
                DB::statement("ALTER TABLE \"car_features\" RENAME COLUMN \"{$from}\" TO \"{$to}\"");
            }
        }
    }
};
