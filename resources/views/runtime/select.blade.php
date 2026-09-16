@php
    $name = (string) $attributes->get('name');
    $id = (string) $attributes->get('id', $name);
    $label = $attributes->get('label');
    $description = $attributes->get('description');
    $error = $attributes->get('error');
    $invalid = $attributes->has('invalid') || $error !== null;
    $controlAttributes = $attributes->except(['label', 'description', 'error', 'invalid']);
@endphp

<div data-studio-component="select" class="flex flex-col gap-1.5">
    @if($label !== null)
        <label for="{{ $id }}" class="text-sm font-medium leading-none text-studio-foreground">{{ $label }}</label>
    @endif

    @if($description !== null)
        <p class="text-sm leading-5 text-studio-foreground-muted">{{ $description }}</p>
    @endif

    <select id="{{ $id }}" @if($invalid) aria-invalid="true" @endif {{ $controlAttributes->class([
        'h-10 w-full rounded-[var(--studio-radius-md)] border bg-studio-surface px-3 text-sm text-studio-foreground outline-none transition focus:border-studio-focus focus:ring-[3px] focus:ring-studio-focus/15 disabled:cursor-not-allowed disabled:opacity-50',
        'border-studio-danger focus:border-studio-danger focus:ring-studio-danger/15' => $invalid,
        'border-studio-border' => ! $invalid,
    ]) }}>
        {{ $slot }}
    </select>

    @if($error !== null)
        <p role="alert" class="text-sm font-medium leading-5 text-studio-danger">{{ $error }}</p>
    @endif
</div>
