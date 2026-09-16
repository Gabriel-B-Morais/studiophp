@php
    $padding = (string) $attributes->get('padding', 'md');
    $paddingClasses = [
        'none' => 'p-0',
        'sm' => 'p-4',
        'md' => 'p-6',
        'lg' => 'p-8',
    ];
@endphp

<section data-studio-component="card" {{ $attributes->except(['padding'])->class(['rounded-[var(--studio-radius-lg)] border border-studio-border bg-studio-surface shadow-[var(--studio-shadow-sm)]', $paddingClasses[$padding] ?? $paddingClasses['md']]) }}>
    {{ $slot }}
</section>
