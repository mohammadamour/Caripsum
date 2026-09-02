@props([
    'variant' => 'default', // 'default', 'white', 'dark'
    'class' => '',
])

@php
    $textColor = match($variant) {
        'white' => '#ffffff',
        'dark' => '#0f172a',
        default => 'var(--accent-color, #0f172a)',
    };
    $markFill = match($variant) {
        'white' => '#ffffff',
        default => 'url(#motoraBrandGrad)',
    };
    $accentFill = match($variant) {
        'white' => 'rgba(255, 255, 255, 0.85)',
        default => '#f97316',
    };
@endphp

<div class="motora-brand-logo flex items-center {{ $class }}" style="display: inline-flex; align-items: center; gap: 0.6rem; text-decoration: none; user-select: none;">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 38 34" width="34" height="30" fill="none" style="flex-shrink: 0;">
        <defs>
            <linearGradient id="motoraBrandGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#fb923c" />
                <stop offset="50%" stop-color="#f97316" />
                <stop offset="100%" stop-color="#ea580c" />
            </linearGradient>
        </defs>
        <!-- Sleek Automotive Winged 'M' Emblem -->
        <path d="M3 28L12 5H20L15.5 17L23.5 5H31.5L23 28H16L19.5 16L11 28H3Z" fill="{{ $markFill }}" />
        <path d="M20 31H33L35 27H22L20 31Z" fill="{{ $accentFill }}" opacity="0.9" />
    </svg>
    <span style="font-family: var(--primary-font, 'Ubuntu', sans-serif); font-size: 1.45rem; font-weight: 800; letter-spacing: 0.06em; color: {{ $textColor }}; line-height: 1; text-transform: uppercase;">
        Motora
    </span>
</div>
