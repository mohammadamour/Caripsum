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
                @include('car._form')
            </form>
        </div>
    </main>
</x-app-layout>
