<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\CarType;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
   use HasFactory, SoftDeletes;

   protected $fillable = [
      'maker_id',
      'model_id',
      'year',
      'price',
      'vin',
      'mileage',
      'car_type_id',
      'fuel_type_id',
      //   'user_id', 
      'city_id',
      'address',
      'phone',
      'description',
      'published_at'
   ];

   public function carType(): BelongsTo
   {
      return $this->belongsTo(CarType::class);
   }

   public function maker(): BelongsTo
   {
      return $this->belongsTo(Maker::class);
   }

   public function fuelType(): BelongsTo
   {
      return $this->belongsTo(FuelType::class);
   }

   public function model(): BelongsTo
   {
      return $this->belongsTo(\App\Models\Model::class);
   }


   public function owner(): BelongsTo
   {
      return $this->belongsTo(User::class, 'user_id');
   }


   public function city(): BelongsTo
   {
      return $this->belongsTo(City::class);
   }



   public function features(): HasOne
   {
      return $this->hasOne(CarFeatures::class);
   }

   public function primaryImage(): HasOne
   {
      return $this->hasOne(CarImages::class)
         ->oldestOfMany('position');
   }

   public function images(): HasMany
   {
      return $this->hasMany(CarImages::class);
   }


   public function favouredUsers(): BelongsToMany
   {
      return $this->belongsToMany(User::class, 'car_favorite', 'car_id', 'user_id')
         ->withTimestamps();
   }

   public function getCreateDate(): string
   {
      return $this->created_at->format('Y-m-d H:i');
   }
}
