<div data-studio-component="field" {{ $attributes->except(['name'])->class('flex flex-col gap-1.5') }}>
    {{ $slot }}
</div>
