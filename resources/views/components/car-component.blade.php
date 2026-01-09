@if ($car)
    <div class="car-item card">
        <a href="{{ route("car.show", $car->id) }}">
            @php
                $imagePath = optional($car->primaryImage)->image_path;
                $rawImageSrc = ! empty($imagePath) ? trim($imagePath) : "/img/cars/Lexus-RX200t-2016/1.jpeg";
                $fallbackImage = "/img/cars/Lexus-RX200t-2016/1.jpeg";

                $resolvedImageSrc = filter_var($rawImageSrc, FILTER_VALIDATE_URL) ? $rawImageSrc : asset($rawImageSrc);

                $resolvedFallback = asset($fallbackImage);
            @endphp

            <img
                src="{{ $resolvedImageSrc }}"
                alt="{{ $car->year ?? "N/A" }} - {{ optional($car->maker)->name ?? "Unknown" }} {{ optional($car->model)->name ?? "Unknown" }}"
                class="car-item-img rounded-t"
                onerror="this.src='{{ $resolvedFallback }}'"
            />
        </a>
        <div class="p-medium">
            <div class="flex items-center justify-between">
                <small class="m-0 text-muted">
                    {{ optional($car->city)->name ?? "Unknown" }}
                </small>
                <button class="btn-heart {{ isset($isFav) && $isFav ? 'btn-heart-active' : '' }}" onclick="toggleFavorite(this, {{ $car->id }})">
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
                            d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"
                        />
                    </svg>
                </button>
            </div>
            <h2 class="car-item-title">
                {{ $car->year ?? "N/A" }} -
                {{ optional($car->maker)->name ?? "Unknown" }}
                {{ optional($car->model)->name ?? "Unknown" }}
            </h2>
            <p class="car-item-price">
                ${{ number_format($car->price ?? 0) }}
            </p>
            <hr />

            <p class="m-0">
                <span class="car-item-badge">{{ $car->carType->name }}</span>
                <span class="car-item-badge">{{ $car->fuelType->name }}</span>
            </p>
        </div>
    </div>
@endif
