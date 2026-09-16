<section data-studio-component="resource" data-studio-model="{{ $attributes->get('model') }}" {{ $attributes->except(['model'])->class('flex flex-col gap-6') }}>
    {{ $slot }}
</section>
