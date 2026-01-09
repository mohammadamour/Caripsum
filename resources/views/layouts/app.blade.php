@props([
    'bodyClass' => null,
    'footerLinks' => '',
    'title' => '',
])
<x-base-layout :$title :$bodyClass :$footerLinks>
    @if ($showHeader ?? true)
        @include("layouts.header")
    @endif

    {{ $slot }}
</x-base-layout>
