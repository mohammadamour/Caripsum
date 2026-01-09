<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Schema::create('car_types', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name', 45);
        // });
            Schema::create('car_types', function (Blueprint $table) {
                $table->id();                    // Auto-incrementing primary key
                $table->string('name', 100);     // VARCHAR(100)
                $table->text('description');     // TEXT (longer content)
                $table->integer('sort_order');   // INTEGER
                $table->boolean('is_active');    // BOOLEAN (true/false)
                $table->decimal('price', 8, 2);  // DECIMAL(8,2) - 8 digits, 2 after decimal
                $table->date('launch_date');     // DATE
                $table->timestamp('last_updated'); // TIMESTAMP
                $table->timestamps();            // created_at & updated_at
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_types');
    }
};
