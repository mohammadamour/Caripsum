<x-app-layout>
        <main>
      <!-- New Cars -->
      <section>
        <div class="container">
          <h2>My Favourite Cars</h2>
          <div class="car-items-listing">


          @foreach($cars as $car)


            @php
                $imagePath = optional($car->primaryImage)->image_path;
                $imageSrc = ! empty($imagePath) ? $imagePath : "/img/cars/Lexus-RX200t-2016/1.jpeg";
                $imageAlt = ($car->year ?? "N/A") . " - " . (optional($car->maker)->name ?? "Unknown") . " " . (optional($car->model)->name ?? "Unknown");
                $location = optional($car->city)->name ?? "Unknown";
                $title = ($car->year ?? "N/A") . " - " . (optional($car->maker)->name ?? "Unknown") . " " . (optional($car->model)->name ?? "Unknown");
                $price = '$' . number_format($car->price ?? 0);
                $badge1 = optional($car->carType)->name ?? null;
                $badge2 = optional($car->fuelType)->name ?? null;
                $carLink = route("car.show", $car);
            @endphp

            <x-car-item
                :imageSrc="$imageSrc"
                :imageAlt="$imageAlt"
                :location="$location"
                :title="$title"
                :price="$price"
                :badge1="$badge1"
                :badge2="$badge2"
                :carLink="$carLink"
                :isInWatchList="true"
                :carId="$car->id"
            />
            @endforeach
          
          </div>

          {{ $cars->onEachSide(1)->links('pagination') }}
        </div>
      </section>
      <!--/ New Cars -->
    </main>
</x-app-layout>