@php
    $name = (string) $attributes->get('name');
    $id = (string) $attributes->get('id', $name);
    $label = $attributes->get('label');
    $description = $attributes->get('description');
    $error = $attributes->get('error');
    $invalid = $attributes->has('invalid') || $error !== null;
    $descriptionId = $description !== null ? $id.'-description' : null;
    $errorId = $error !== null ? $id.'-error' : null;
    $describedBy = implode(' ', array_filter([$descriptionId, $errorId]));
    $controlAttributes = $attributes->except(['label', 'description', 'error', 'invalid']);
@endphp

<div data-studio-component="textarea" class="flex flex-col gap-1.5">
    @if($label !== null)
        <label for="{{ $id }}" class="text-sm font-medium leading-none text-studio-foreground">{{ $label }}</label>
    @endif

    @if($description !== null)
        <p id="{{ $descriptionId }}" class="text-sm leading-5 text-studio-foreground-muted">{{ $description }}</p>
    @endif

    <textarea
        id="{{ $id }}"
        @if($invalid) aria-invalid="true" @endif
        @if($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif
        {{ $controlAttributes->class([
            'min-h-28 w-full resize-y rounded-[var(--studio-radius-md)] border bg-studio-surface px-3 py-2 text-sm text-studio-foreground outline-none transition placeholder:text-studio-foreground-muted/70 focus:border-studio-focus focus:ring-[3px] focus:ring-studio-focus/15 disabled:cursor-not-allowed disabled:opacity-50 read-only:bg-studio-surface-muted',
            'border-studio-danger focus:border-studio-danger focus:ring-studio-danger/15' => $invalid,
            'border-studio-border' => ! $invalid,
        ]) }}>{{ $slot }}</textarea>

    @if($error !== null)
        <p id="{{ $errorId }}" role="alert" class="text-sm font-medium leading-5 text-studio-danger">{{ $error }}</p>
    @endif
</div>
