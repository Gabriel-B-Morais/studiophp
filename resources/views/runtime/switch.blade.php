@php
    $name = (string) $attributes->get('name');
    $id = (string) $attributes->get('id', $name);
    $label = $attributes->get('label');
    $description = $attributes->get('description');
@endphp

<div data-studio-component="switch" {{ $attributes->only('class')->class('flex items-start justify-between gap-4') }}>
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

    <label class="relative inline-flex shrink-0 cursor-pointer items-center">
        <input id="{{ $id }}" type="checkbox" role="switch" class="peer sr-only" {{ $attributes->except(['label', 'description', 'class']) }}>
        <span aria-hidden="true" class="h-6 w-11 rounded-full bg-studio-border-strong transition peer-checked:bg-studio-primary peer-disabled:cursor-not-allowed peer-disabled:opacity-50 after:absolute after:left-0.5 after:top-0.5 after:size-5 after:rounded-full after:bg-white after:shadow-sm after:transition peer-checked:after:translate-x-5"></span>
    </label>
</div>
