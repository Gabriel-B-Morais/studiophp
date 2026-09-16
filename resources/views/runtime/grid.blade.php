@php
    $columns = (string) $attributes->get('cols', '1');
    $gap = (string) $attributes->get('gap', 'md');
    $columnClasses = [
        '1' => 'grid-cols-1',
        '2' => 'grid-cols-1 md:grid-cols-2',
        '3' => 'grid-cols-1 md:grid-cols-2 xl:grid-cols-3',
        '4' => 'grid-cols-1 md:grid-cols-2 xl:grid-cols-4',
    ];
    $gapClasses = [
        'sm' => 'gap-3',
        'md' => 'gap-5',
        'lg' => 'gap-8',
    ];
@endphp

<div data-studio-component="grid" {{ $attributes->except(['cols', 'gap'])->class(['grid', $columnClasses[$columns] ?? $columnClasses['1'], $gapClasses[$gap] ?? $gapClasses['md']]) }}>
    {{ $slot }}
</div>
