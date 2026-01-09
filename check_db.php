use App\Models\Car;

$cars = Car::with('primaryImage')->get();
$total = $cars->count();
$missing = $cars->whereNull('primaryImage')->count();
$withImage = $cars->whereNotNull('primaryImage')->count();

echo "Total Cars: $total\n";
echo "With Primary Image: $withImage\n";
echo "Missing Primary Image: $missing\n";

if ($missing > 0) {
    $badCar = $cars->whereNull('primaryImage')->first();
    echo "Sample missing ID: " . $badCar->id . "\n";
    echo "Car Created At: " . $badCar->created_at . "\n";
    echo "Images count for this car: " . $badCar->images()->count() . "\n";
}

$first = $cars->whereNotNull('primaryImage')->first();
if ($first) {
    echo "Sample Image URL: " . $first->primaryImage->image_path . "\n";
    echo "Sample Image Position: " . $first->primaryImage->position . "\n";
}
