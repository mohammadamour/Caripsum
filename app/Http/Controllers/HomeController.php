<?php

namespace App\Http\Controllers;

use App\Models\carTypes;
use App\Models\Car;
use App\Models\CarFeatures;
use App\Models\CarImages;
use App\Models\CarType;
use App\Models\FuelType;
use App\Models\Maker;
use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class HomeController extends Controller
{
    public function index()
    {
        $cars = Car::where('published_at', '<', now())
            ->with(['primaryImage', 'maker', 'model', 'city', 'carType', 'fuelType'])
            ->orderBy('published_at', 'desc')
            ->limit(30)
            ->get();

        $favIds = auth()->check() ? auth()->user()->favouriteCars()->pluck('car_id')->toArray() : [];

        return view('index', ['cars' => $cars, 'favIds' => $favIds]);
    }
}
