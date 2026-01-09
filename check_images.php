use App\Models\Car;

$car = Car::with('images')->first();

echo "Car ID: " . $car->id . "\n";
echo "Image Count: " . $car->images->count() . "\n";

foreach ($car->images as $img) {
    echo "Pos: " . $img->position . " | URL: " . $img->image_path . "\n";
}
