<x-app-layout>
    <main>
        <div class="container-small">
            <h1 class="car-details-page-title">Edit car</h1>
            <form
                action="{{ route('car.update', $car) }}"
                method="POST"
                enctype="multipart/form-data"
                class="card add-new-car-form"
            >
                @method('PUT')
                @csrf
                @include('car._form')
            </form>
        </div>
    </main>
</x-app-layout>
