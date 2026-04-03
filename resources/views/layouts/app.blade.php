<x-base-layout :title="$title" :css-class="$bodyClass">
    @if ($showHeader ?? true)
        @include("layouts.header")
    @endif

    {{ $slot }}
</x-base-layout>
