@php
    $gap = (string) $attributes->get('gap', 'md');
    $gaps = [
        'xs' => 'gap-1.5',
        'sm' => 'gap-3',
        'md' => 'gap-5',
        'lg' => 'gap-8',
        'xl' => 'gap-12',
    ];
@endphp

<div data-studio-component="stack" {{ $attributes->except(['gap'])->class(['flex flex-col', $gaps[$gap] ?? $gaps['md']]) }}>
    {{ $slot }}
</div>
