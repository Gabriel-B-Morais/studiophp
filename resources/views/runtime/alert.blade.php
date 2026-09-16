@php
    $variant = (string) $attributes->get('variant', 'info');
    $styles = [
        'info' => 'border-studio-info/25 bg-studio-info/8 text-studio-foreground',
        'success' => 'border-studio-success/25 bg-studio-success/8 text-studio-foreground',
        'warning' => 'border-studio-warning/30 bg-studio-warning/10 text-studio-foreground',
        'danger' => 'border-studio-danger/25 bg-studio-danger/8 text-studio-foreground',
    ];
    $role = $variant === 'danger' ? 'alert' : 'status';
@endphp

<div data-studio-component="alert" role="{{ $role }}" {{ $attributes->except(['variant'])->class(['rounded-[var(--studio-radius-md)] border px-4 py-3 text-sm leading-6', $styles[$variant] ?? $styles['info']]) }}>
    {{ $slot }}
</div>
