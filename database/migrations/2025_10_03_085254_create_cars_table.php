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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->integer('price');
            $table->integer('mileage');
            $table->string('name', 45);
            $table->string('vin', 255);
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('maker_id')->constrained('makers');
            $table->foreignId('fuel_type_id')->constrained('fuel_types');
            $table->foreignId('model_id')->constrained('models');
            $table->foreignId('city_id')->constrained('cities');
            $table->string('address', 255);
            $table->string('phone', 45);
            $table->timestamps();
            $table->longText('description')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
