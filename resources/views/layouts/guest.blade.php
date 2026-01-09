@props([
    "title",
    "bodyClass",
    "showHeader" => false,
])

<x-auth-layout
    :title="$title"
    :bodyClass="$bodyClass"
    :showHeader="$showHeader"
>
    {{ $slot }}
</x-auth-layout>
