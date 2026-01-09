<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>
            {{ $title ?? View::yieldContent('title', 'Home') }} |
        Logoipsum
    </title>
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap"
        rel="stylesheet" />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer" />
    <!-- <link
      href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css"
      rel="stylesheet"
    /> -->

    <link rel="stylesheet" href="{{ asset("/css/app.css") }}" />
    <!-- <link rel="stylesheet" href="css/output.css" /> -->
</head>

<body class="{{ $cssClass ?? "" }}">
    {{-- @yield('childContent') --}}
    {{ $slot }}

    @include('layouts.flash')

    @if($showFooter ?? true)
    @include('layouts.footer')
    @endif

    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/scrollReveal.js/4.0.9/scrollreveal.js"
        integrity="sha512-XJgPMFq31Ren4pKVQgeD+0JTDzn0IwS1802sc+QTZckE6rny7AN2HLReq6Yamwpd2hFe5nJJGZLvPStWFv5Kww=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset("/js/app.js") }}"></script>
    <script>
        function toggleFavorite(button, carId) {
            // Determine logic based on class state or handle response
            // Actually, let's just send the request
            const isGuest = '{{ auth()->check() ? "false" : "true" }}' === 'true';
            if (isGuest) {
                window.location.href = '{{ route("login") }}';
                return;
            }

            fetch(`/car/${carId}/watchlist`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.added) {
                        button.querySelector('svg:first-child').classList.add('hidden');
                        button.querySelector('svg:last-child').classList.remove('hidden');
                    } else {
                        button.querySelector('svg:first-child').classList.remove('hidden');
                        button.querySelector('svg:last-child').classList.add('hidden');
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
</body>

</html>