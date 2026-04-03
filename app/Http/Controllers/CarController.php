<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\User;
use App\Models\Maker;
use App\Models\CarType;
use App\Models\FuelType;
use App\Models\City;
use App\Models\State;
use App\Models\CarFeatures;
use App\Models\CarImages;
use App\Models\Model;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
 
        $cars = auth()->user()->cars()
            ->with(['maker', 'model', 'city', 'carType', 'fuelType', 'primaryImage'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

            // $cars = User::find(1)
            // ->cars()
            // ->orderBy('created_at', 'desc')
            // ->get();


            
            
        $favIds = auth()->check() ? auth()->user()->favouriteCars()->pluck('car_id')->toArray() : [];
        return view('car.index', ['cars' => $cars, 'favIds' => $favIds]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $makers = Maker::all();
        $carTypes = CarType::all();
        $fuelTypes = FuelType::all();
        $states = State::all();
        $cities = City::all(); // Ideally filtered by state via AJAX, but for now passing all

        return view('car.create', compact('makers', 'carTypes', 'fuelTypes', 'states', 'cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validate Form Data
        $validatedData = $request->validate([
            'maker_id' => 'required|exists:makers,id',
            'model_id' => 'required|exists:models,id',
            'year' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'car_type_id' => 'required|exists:car_types,id',
            'price' => 'required|integer|min:0',
            'vin' => 'required|string|max:255',
            'mileage' => 'required|integer|min:0',
            'fuel_type_id' => 'required|exists:fuel_types,id',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:45',
            'description' => 'nullable|string',
            'published' => 'nullable|in:on,1', // Checkbox sends 'on' or nothing
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate each image
        ]);

        // 2. Create Car Record
        $car = new Car();
        $car->fill($validatedData);
        $car->user_id = auth()->id();
        $car->published_at = $request->has('published') ? now() : null;
        $car->save();

        // 3. Create Car Features
        // We can just create with all request data; non-matching keys are ignored by mass assignment if configured, 
        // but explicit mapping is safer.
        $featuresData = [
            'abs' => $request->has('abs'),
            'air_conditioning' => $request->has('air_conditioning'),
            'power_windows' => $request->has('power_windows'),
            'power_doors_locks' => $request->has('power_door_locks'),
            'cruise_control' => $request->has('cruise_control'),
            'bluetooth-connectivity' => $request->has('bluetooth_connectivity'), 
            'remote_start' => $request->has('remote_start'),
            'gps_navigation' => $request->has('gps_navigation'),
            'heated_seats' => $request->has('heated_seats'),
            'climate_control' => $request->has('climate_control'),
            'rear_parking_sensors' => $request->has('rear_parking_sensors'),
            'leather_seats' => $request->has('leather_seats'),
        ];
        
        // Handle the dash in column name if necessary by creating manually or using array
        $features = new CarFeatures($featuresData);
        $features->car_id = $car->id;
        $features->save();


        // 4. Handle Image Uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store("img/cars/{$car->id}", 'public');

                CarImages::create([
                    'car_id' => $car->id,
                    'image_path' => "/storage/" . $path,
                    'position' => $index + 1
                ]);
            }
        }

        return redirect()->route('car.index')
            ->with('success', 'Car created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Car $car)
    {
        $car->load([
            'maker',
            'model',
            'city',
            'carType',
            'fuelType',
            'primaryImage',
            'images',
            'features',
            'owner' => function ($query) {
                $query->withCount('cars');
            },
        ]);

        return view('car.show', ['car' => $car]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Car $car)
    {
        if ($car->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $makers = Maker::all();
        $carTypes = CarType::all();
        $fuelTypes = FuelType::all();
        $states = State::all();
        $cities = City::all();

        return view('car.edit', compact('car', 'makers', 'carTypes', 'fuelTypes', 'states', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Car $car)
    {
        if ($car->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // 1. Validate Form Data
        $validatedData = $request->validate([
            'maker_id' => 'required|exists:makers,id',
            'model_id' => 'required|exists:models,id',
            'year' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'car_type_id' => 'required|exists:car_types,id',
            'price' => 'required|integer|min:0',
            'vin' => 'required|string|max:255',
            'mileage' => 'required|integer|min:0',
            'fuel_type_id' => 'required|exists:fuel_types,id',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:45',
            'description' => 'nullable|string',
            'published' => 'nullable|in:on,1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // 2. Update Car Record
        $car->fill($validatedData);
        $car->published_at = $request->has('published') ? now() : null;
        $car->save();

        // 3. Update Car Features
        $featuresData = [
            'abs' => $request->has('abs'),
            'air_conditioning' => $request->has('air_conditioning'),
            'power_windows' => $request->has('power_windows'),
            'power_doors_locks' => $request->has('power_door_locks'),
            'cruise_control' => $request->has('cruise_control'),
            'bluetooth-connectivity' => $request->has('bluetooth_connectivity'), 
            'remote_start' => $request->has('remote_start'),
            'gps_navigation' => $request->has('gps_navigation'),
            'heated_seats' => $request->has('heated_seats'),
            'climate_control' => $request->has('climate_control'),
            'rear_parking_sensors' => $request->has('rear_parking_sensors'),
            'leather_seats' => $request->has('leather_seats'),
        ];

        // Update or Create features
        if ($car->features) {
            $car->features->update($featuresData);
        } else {
            $features = new CarFeatures($featuresData);
            $features->car_id = $car->id;
            $features->save();
        }

        // 4. Handle Image Uploads (Append new images)
        if ($request->hasFile('images')) {
            $currentMaxPosition = $car->images()->max('position') ?? 0;
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store("img/cars/{$car->id}", 'public');

                CarImages::create([
                    'car_id' => $car->id,
                    'image_path' => "/storage/" . $path,
                    'position' => $currentMaxPosition + $index + 1
                ]);
            }
        }

        return redirect()->route('car.index')
            ->with('success', 'Car updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car)
    {
        if ($car->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $car->delete();

        return redirect()->route('car.index')
            ->with('success', 'Car deleted successfully!');
    }

    public function search(Request $request)
    {
        $query = Car::where('published_at', '<', now()) 
            ->with(['maker', 'model', 'city', 'carType', 'fuelType', 'primaryImage']);

        // Filter by Maker
        if ($request->filled('maker_id')) {
            $query->where('maker_id', $request->maker_id);
        }

        // Filter by Model
        if ($request->filled('model_id')) {
            $query->where('model_id', $request->model_id);
        }

        // Filter by City
        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        // Filter by State
        if ($request->filled('state_id')) {
            $query->whereHas('city', function($q) use ($request) {
                $q->where('state_id', $request->state_id);
            });
        }

        // Filter by Car Type
        if ($request->filled('car_type_id')) {
            $query->where('car_type_id', $request->car_type_id);
        }

        // Filter by Fuel Type
        if ($request->filled('fuel_type_id')) {
            $query->where('fuel_type_id', $request->fuel_type_id);
        }

        // Filter by Year Range
        if ($request->filled('year_from')) {
            $query->where('year', '>=', $request->year_from);
        }
        if ($request->filled('year_to')) {
            $query->where('year', '<=', $request->year_to);
        }

        // Filter by Price Range
        if ($request->filled('price_from')) {
            $query->where('price', '>=', $request->price_from);
        }
        if ($request->filled('price_to')) {
            $query->where('price', '<=', $request->price_to);
        }
        
        // Filter by Mileage
        if ($request->filled('mileage')) {
            $query->where('mileage', '<=', $request->mileage);
        }

        // Sorting
        if ($request->filled('sort')) {
            $sort = $request->sort;
            if ($sort === 'price') {
                $query->orderBy('price', 'asc');
            } elseif ($sort === '-price') {
                $query->orderBy('price', 'desc');
            } else {
                $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Get dropdown data for the view
        $makers = Maker::all();
        $carTypes = CarType::all();
        $fuelTypes = FuelType::all();
        $states = State::all();
        $cities = City::all();
        $models = Model::all();

        $cars = $query->paginate(15);

        $favIds = auth()->check() ? auth()->user()->favouriteCars()->pluck('car_id')->toArray() : [];

        return view('car.search', [
            'cars' => $cars, 
            'makers' => $makers,
            'carTypes' => $carTypes,
            'models' => $models,
            'fuelTypes' => $fuelTypes,
            'states' => $states,
            'cities' => $cities,
            'favIds' => $favIds
        ]);
    }

    public function watchlist()
    {
        $user = auth()->user();

        $cars = $user
        ->favouriteCars()
        ->with(['maker', 'model', 'city', 'carType', 'fuelType', 'primaryImage'])
        ->paginate(15);
        
        // Pass favIds just in case, though view sets it to true
        $favIds = $cars->pluck('id')->toArray();

        return view('car.watchlist', ['cars' => $cars, 'favIds' => $favIds]);
    }

    public function toggleWatchlist(Car $car)
    {
        $user = auth()->user();
        
        // Toggle the relationship (attach if missing, detach if exists)
        $toggled = $user->favouriteCars()->toggle($car->id);
        
        // Return JSON response for AJAX
        return response()->json([
            'added' => count($toggled['attached']) > 0
        ]);
    }
}