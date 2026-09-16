@php
    $variant = (string) $attributes->get('variant', 'primary');
    $size = (string) $attributes->get('size', 'md');
    $loading = $attributes->has('loading');
    $variants = [
        'primary' => 'bg-studio-primary text-studio-primary-foreground hover:opacity-90',
        'secondary' => 'border border-studio-border bg-studio-surface text-studio-foreground hover:bg-studio-surface-muted',
        'ghost' => 'bg-transparent text-studio-foreground hover:bg-studio-surface-muted',
        'danger' => 'bg-studio-danger text-white hover:opacity-90',
    ];
    $sizes = [
        'sm' => 'h-8 px-3 text-xs',
        'md' => 'h-10 px-4 text-sm',
        'lg' => 'h-11 px-5 text-sm',
    ];
@endphp

<button
    data-studio-component="button"
    type="{{ $attributes->get('type', 'button') }}"
    @if($loading) aria-busy="true" disabled @endif
    {{ $attributes->except(['variant', 'size', 'loading', 'type'])->class([
        'inline-flex items-center justify-center gap-2 rounded-[var(--studio-radius-md)] font-medium outline-none transition focus-visible:ring-[3px] focus-visible:ring-studio-focus/20 disabled:pointer-events-none disabled:opacity-50',
        $variants[$variant] ?? $variants['primary'],
        $sizes[$size] ?? $sizes['md'],
    ]) }}
>
    @if($loading)
        <span aria-hidden="true" class="size-4 animate-spin rounded-full border-2 border-current border-r-transparent"></span>
    @endif
    {{ $slot }}
</button>
