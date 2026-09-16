@php
    $size = (string) $attributes->get('size', 'lg');
    $sizes = [
        'sm' => 'max-w-2xl',
        'md' => 'max-w-4xl',
        'lg' => 'max-w-6xl',
        'xl' => 'max-w-7xl',
        'full' => 'max-w-none',
    ];
@endphp

<div data-studio-component="container" {{ $attributes->except(['size'])->class(['mx-auto w-full px-4 py-8 sm:px-6 lg:px-8', $sizes[$size] ?? $sizes['lg']]) }}>
    {{ $slot }}
</div>
