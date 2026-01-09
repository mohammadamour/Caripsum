<x-app-layout>
    <div class="container my-large">
      <div class="success-message">Success message when something happens</div>
    </div>

    <main>
      <div>
        <div class="container">
          <h1 class="car-details-page-title">My Cars</h1>
          <div class="card p-medium">
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Published</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($cars as $car)
                  <tr>
                    <td>
                      <img
                        src="{{ $car->primaryImage?->image_path }}"
                        alt=""
                        class="my-cars-img-thumbnail"
                      />
                    </td>
                    <td>{{ $car->year }} - {{ $car->maker->name }} {{ $car->model->name }}</td>
                    <td> {{ $car->getCreateDate() }}</td>
                    <td>{{ $car->published_at ? 'Yes' : 'No' }}</td>
                    <td class="">
                      <a
                        href="{{ route('car.edit', $car) }}"
                        class="btn btn-edit inline-flex items-center"
                      >
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke-width="1.5"
                          stroke="currentColor"
                          style="width: 12px; margin-right: 5px"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"
                          />
                        </svg>

                        edit
                      </a>
                      <form action="{{ route('car.destroy', $car) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this car?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-delete inline-flex items-center">
                            <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            style="width: 12px; margin-right: 5px"
                            >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                            />
                            </svg>

                            delete
                        </button>
                      </form>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="5" class="text-center p-large">you have no cars. <a href="{{ route('car.create') }}">Add a car</a></td>
                  </tr>
                  @endforelse

                </tbody>
              </table>
            </div>

            {{ $cars->onEachSide(1)->links('pagination') }}
          </div>
        </div>
      </div>
    </main>
</x-app-layout>
