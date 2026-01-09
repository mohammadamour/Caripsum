@props([
    "color",
    "bgColor" => "white",
])

<div
    {{
        $attributes
            ->class("card card-text-$color card-bg-$bgColor")
            ->merge(["class" => "card"])
    }}
>
    <div {{ $header->attributes->class("card-header") }}>
        {{ $header ?? "" }}
    </div>

    @if ($slot->isEmpty())
        <p>please provide some content</p>
    @else
        {{ $slot }}
    @endif

    <div class="card-footer">
        {{ $footer ?? "" }}
    </div>
</div>
