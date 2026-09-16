@php
    $name = (string) $attributes->get('name');
    $id = (string) $attributes->get('id', $name);
    $label = $attributes->get('label');
    $description = $attributes->get('description');
@endphp

<div data-studio-component="checkbox" {{ $attributes->only('class')->class('flex items-start gap-3') }}>
    <input id="{{ $id }}" type="checkbox" {{ $attributes->except(['label', 'description', 'class'])->class('mt-0.5 size-4 rounded border-studio-border text-studio-primary accent-[var(--studio-primary)] focus:ring-2 focus:ring-studio-focus/20 disabled:cursor-not-allowed disabled:opacity-50') }}>
    @if($label !== null || $description !== null)
        <div class="grid gap-1">
            @if($label !== null)
                <label for="{{ $id }}" class="text-sm font-medium leading-none text-studio-foreground">{{ $label }}</label>
            @endif
            @if($description !== null)
                <p class="text-sm leading-5 text-studio-foreground-muted">{{ $description }}</p>
            @endif
        </div>
    @endif
</div>
