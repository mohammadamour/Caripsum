<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Model extends EloquentModel
{
    use HasFactory;

    protected static function newFactory(): Factory
    {
        return \Database\Factories\ModelFactory::new();
    }

    protected $fillable = ['name', 'maker_id'];

    public $timestamps = false;

    public function maker(): BelongsTo
    {
        return $this->belongsTo(Maker::class);
    }

        public function cars(): HasMany      
    {
        return $this->hasMany(Car::class);
    }
    
}