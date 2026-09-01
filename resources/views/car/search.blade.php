<x-app-layout>
    <main>
        <!-- Found Cars -->
        <section>
            <div class="container">
                <div class="sm:flex items-center justify-between mb-medium">
                    <div class="flex items-center">
                        <button class="show-filters-button flex items-center">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                style="width: 20px"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M6 13.5V3.75m0 9.75a1.5 1.5 0 0 1 0 3m0-3a1.5 1.5 0 0 0 0 3m0 3.75V16.5m12-3V3.75m0 9.75a1.5 1.5 0 0 1 0 3m0-3a1.5 1.5 0 0 0 0 3m0 3.75V16.5m-6-9V3.75m0 3.75a1.5 1.5 0 0 1 0 3m0-3a1.5 1.5 0 0 0 0 3m0 9.75V10.5"
                                />
                            </svg>
                            Filters
                        </button>
                        <h2>Define your search criteria</h2>
                    </div>

                    <select class="sort-dropdown" aria-label="Sort cars">
                        <option value="newest" @selected(request('sort', 'newest') === 'newest')>Newest Listings</option>
                        <option value="oldest" @selected(request('sort') === 'oldest')>Oldest Listings</option>
                        <option value="price_asc" @selected(request('sort') === 'price_asc')>Price: Low to High</option>
                        <option value="price_desc" @selected(request('sort') === 'price_desc')>Price: High to Low</option>
                        <option value="year_desc" @selected(request('sort') === 'year_desc')>Year: Newest First</option>
                        <option value="year_asc" @selected(request('sort') === 'year_asc')>Year: Oldest First</option>
                        <option value="mileage_asc" @selected(request('sort') === 'mileage_asc')>Lowest Mileage</option>
                        <option value="mileage_desc" @selected(request('sort') === 'mileage_desc')>Highest Mileage</option>
                    </select>
                </div>
                <div class="search-car-results-wrapper">
                    <div class="search-cars-sidebar">
                        <div class="card card-found-cars">
                            <p class="found-cars-count">
                                Found
                                <strong>{{ $cars->total() }}</strong>
                                cars
                            </p>

                            <button class="close-filters-button">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="currentColor"
                                    style="width: 24px"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </button>
                        </div>

                        <!-- Find a car form -->
                        <section class="find-a-car">
                            <form
                                action="{{ route("car.search") }}"
                                method="GET"
                                class="find-a-car-form card flex p-medium"
                            >
                                <div class="find-a-car-inputs">
                                    <div class="form-group">
                                        <label class="mb-medium">Search</label>
                                        <input type="search" name="q" placeholder="Make, model, city, keyword" value="{{ request('q') }}" />
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-medium">Maker</label>
                                        <select id="makerSelect" name="maker_id">
                                            <option value="">Maker</option>
                                            @foreach($makers as $maker)
                                                <option value="{{ $maker->id }}" @selected(request('maker_id') == $maker->id)>{{ $maker->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-medium">Model</label>
                                        <select id="modelSelect" name="model_id">
                                            <option value="" style="display: block">Model</option>
                                            @foreach($models as $model)
                                                <option value="{{ $model->id }}" data-parent="{{ $model->maker_id }}" style="display: none" @selected(request('model_id') == $model->id)>{{ $model->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-medium">Type</label>
                                        <select name="car_type_id">
                                            <option value="">Type</option>
                                            @foreach($carTypes as $type)
                                                <option value="{{ $type->id }}" @selected(request('car_type_id') == $type->id)>{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-medium">Year</label>
                                        <div class="flex gap-1">
                                            <input type="number" placeholder="Year From" name="year_from" value="{{ request('year_from') }}" min="1990" />
                                            <input type="number" placeholder="Year To" name="year_to" value="{{ request('year_to') }}" min="1990" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-medium">Price</label>
                                        <div class="flex gap-1">
                                            <input type="number" placeholder="Price From" name="price_from" value="{{ request('price_from') }}" min="0" />
                                            <input type="number" placeholder="Price To" name="price_to" value="{{ request('price_to') }}" min="0" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-medium">Mileage</label>
                                        <div class="flex gap-1">
                                            <input type="number" placeholder="Min miles" name="mileage_from" value="{{ request('mileage_from') }}" min="0" />
                                            <input type="number" placeholder="Max miles" name="mileage_to" value="{{ request('mileage_to') }}" min="0" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-medium">State</label>
                                        <select id="stateSelect" name="state_id">
                                            <option value="">State/Region</option>
                                            @foreach($states as $state)
                                                <option value="{{ $state->id }}" @selected(request('state_id') == $state->id)>{{ $state->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-medium">City</label>
                                        <select id="citySelect" name="city_id">
                                            <option value="" style="display: block">City</option>
                                            @foreach($cities as $city)
                                                <option value="{{ $city->id }}" data-parent="{{ $city->state_id }}" style="display: none" @selected(request('city_id') == $city->id)>{{ $city->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-medium">Fuel Type</label>
                                        <select name="fuel_type_id">
                                            <option value="">Fuel Type</option>
                                            @foreach($fuelTypes as $fuelType)
                                                <option value="{{ $fuelType->id }}" @selected(request('fuel_type_id') == $fuelType->id)>{{ $fuelType->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="flex">
                                    <a
                                        href="{{ route('car.search') }}"
                                        class="btn btn-find-a-car-reset"
                                    >
                                        Reset
                                    </a>
                                    <button
                                        class="btn btn-primary btn-find-a-car-submit"
                                    >
                                        Search
                                    </button>
                                </div>
                            </form>
                        </section>
                        <!--/ Find a car form -->
                    </div>

                    <div class="search-cars-results">
                        <div class="car-items-listing">
                            @forelse ($cars as $car)
                                @php
                                    $imagePath = optional($car->primaryImage)->image_path;
                                    $imageSrc = ! empty($imagePath) ? $imagePath : "/img/cars/Lexus-RX200t-2016/1.jpeg";
                                    $imageAlt = ($car->year ?? "N/A") . " - " . (optional($car->maker)->name ?? "Unknown") . " " . (optional($car->model)->name ?? "Unknown");
                                    $location = optional($car->city)->name ?? "Unknown";
                                    $title = ($car->year ?? "N/A") . " - " . (optional($car->maker)->name ?? "Unknown") . " " . (optional($car->model)->name ?? "Unknown");
                                    $price = '$' . number_format($car->price ?? 0);
                                    $badge1 = optional($car->carType)->name ?? null;
                                    $badge2 = optional($car->fuelType)->name ?? null;
                                    $carLink = route("car.show", $car->id);
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
                                    :carId="$car->id"
                                    :isInWatchList="in_array($car->id, $favIds)"
                                />
                            @empty
                                <div class="card p-medium">
                                    <p>No cars found matching your criteria.</p>
                                </div>
                            @endforelse
                        </div>
                        {{  $cars->onEachSide(1)->links() }}
                    </div>
                </div>
            </div>
        </section>
        <!--/ Found Cars -->
    </main>
</x-app-layout>
