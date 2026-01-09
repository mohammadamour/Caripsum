<x-app-layout>
    <main>
        <div class="container-small">
            <h1 class="car-details-page-title">Add new car</h1>
            <form
                action="{{ route('car.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="card add-new-car-form"
            >
                @csrf
                <div class="form-content">
                    <div class="form-details">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Maker</label>
                                    <select name="maker_id" id="makerSelect">
                                        <option value="">Maker</option>
                                        @foreach($makers as $maker)
                                            <option value="{{ $maker->id }}" {{ old('maker_id') == $maker->id ? 'selected' : '' }}>{{ $maker->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('maker_id')
                                    <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Model</label>
                                    <select name="model_id" id="modelSelect">
                                        <option value="">Model</option>
                                        {{-- TODO: Dynamic loading or populate all --}}
                                        @foreach($makers as $maker)
                                            @foreach($maker->models as $model)
                                                <option value="{{ $model->id }}" data-parent="{{ $maker->id }}" {{ old('model_id') == $model->id ? 'selected' : '' }}>{{ $model->name }}</option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                    @error('model_id')
                                    <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Year</label>
                                    <select name="year">
                                        <option value="">Year</option>
                                        @for($i = date('Y'); $i >= 1990; $i--)
                                            <option value="{{ $i }}" {{ old('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                    @error('year')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Car Type</label>
                            <div class="row">
                                @foreach($carTypes as $type)
                                <div class="col">
                                    <label class="inline-radio">
                                        <input
                                            type="radio"
                                            name="car_type_id"
                                            value="{{ $type->id }}"
                                            {{ old('car_type_id') == $type->id ? 'checked' : '' }}
                                        />
                                        {{ $type->name }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Price</label>
                                    <input type="number" name="price" placeholder="Price" value="{{ old('price') }}" />
                                    @error('price')<p class="error-message">{{ $message }}</p>@enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Vin Code</label>
                                    <input name="vin" placeholder="Vin Code" value="{{ old('vin') }}" />
                                    @error('vin')<p class="error-message">{{ $message }}</p>@enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Mileage (ml)</label>
                                    <input name="mileage" placeholder="Mileage" value="{{ old('mileage') }}" />
                                    @error('mileage')<p class="error-message">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Fuel Type</label>
                            <div class="row">
                                @foreach($fuelTypes as $fuel)
                                <div class="col">
                                    <label class="inline-radio">
                                        <input
                                            type="radio"
                                            name="fuel_type_id"
                                            value="{{ $fuel->id }}"
                                            {{ old('fuel_type_id') == $fuel->id ? 'checked' : '' }}
                                        />
                                        {{ $fuel->name }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>State/Region</label>
                                    <select name="state_id" id="stateSelect">
                                        <option value="">State/Region</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state->id }}"{{ old('state_id') == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('state_id')<p class="error-message">{{ $message }}</p>@enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>City</label>
                                    <select name="city_id" id="citySelect">
                                        <option value="">City</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}" data-parent="{{ $city->state_id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                     @error('city_id')<p class="error-message">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                            <div class="col">
                                <div class="form-group">
                                    <label>Address</label>
                                    <input name="address" placeholder="Address" value="{{ old('address') }}" />
                                    @error('address')<p class="error-message">{{ $message }}</p>@enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input name="phone" placeholder="Phone" value="{{ old('phone') }}" />
                                    @error('phone')<p class="error-message">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <label class="checkbox">
                                        <input
                                            type="checkbox"
                                            name="air_conditioning"
                                            value="1"
                                        />
                                        Air Conditioning
                                    </label>

                                    <label class="checkbox">
                                        <input
                                            type="checkbox"
                                            name="power_windows"
                                            value="1"
                                        />
                                        Power Windows
                                    </label>

                                    <label class="checkbox">
                                        <input
                                            type="checkbox"
                                            name="power_door_locks"
                                            value="1"
                                        />
                                        Power Door Locks
                                    </label>

                                    <label class="checkbox">
                                        <input
                                            type="checkbox"
                                            name="abs"
                                            value="1"
                                        />
                                        ABS
                                    </label>

                                    <label class="checkbox">
                                        <input
                                            type="checkbox"
                                            name="cruise_control"
                                            value="1"
                                        />
                                        Cruise Control
                                    </label>

                                    <label class="checkbox">
                                        <input
                                            type="checkbox"
                                            name="bluetooth_connectivity"
                                            value="1"
                                        />
                                        Bluetooth Connectivity
                                    </label>
                                </div>
                                <div class="col">
                                    <label class="checkbox">
                                        <input
                                            type="checkbox"
                                            name="remote_start"
                                            value="1"
                                        />
                                        Remote Start
                                    </label>

                                    <label class="checkbox">
                                        <input
                                            type="checkbox"
                                            name="gps_navigation"
                                            value="1"
                                        />
                                        GPS Navigation System
                                    </label>

                                    <label class="checkbox">
                                        <input
                                            type="checkbox"
                                            name="heated_seats"
                                            value="1"
                                        />
                                        Heated Seats
                                    </label>

                                    <label class="checkbox">
                                        <input
                                            type="checkbox"
                                            name="climate_control"
                                            value="1"
                                        />
                                        Climate Control
                                    </label>

                                    <label class="checkbox">
                                        <input
                                            type="checkbox"
                                            name="rear_parking_sensors"
                                            value="1"
                                        />
                                        Rear Parking Sensors
                                    </label>

                                    <label class="checkbox">
                                        <input
                                            type="checkbox"
                                            name="leather_seats"
                                            value="1"
                                        />
                                        Leather Seats
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                                <label>Detailed Description</label>
                                <textarea name="description" rows="10">{{ old('description') }}</textarea>
                            </div>
                        <div class="form-group">
                            <label class="checkbox">
                                <input type="checkbox" name="published" />
                                Published
                            </label>
                        </div>
                    </div>
                    <div class="form-images">
                        <div class="form-image-upload">
                            <div class="upload-placeholder">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    style="width: 48px"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                                    />
                                </svg>
                            </div>
                            <input
                                id="carFormImageUpload"
                                type="file"
                                name="images[]"
                                multiple
                            />
                        </div>
                        <div id="imagePreviews" class="car-form-images"></div>
                    </div>
                </div>
                <div class="p-medium" style="width: 100%">
                    <div class="flex justify-end gap-1">
                        <button type="button" class="btn btn-default">
                            Reset
                        </button>
                        <button class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </main>
</x-app-layout>
