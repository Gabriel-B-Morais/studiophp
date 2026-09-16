<form data-studio-component="form" method="{{ $attributes->get('method', 'POST') }}" {{ $attributes->except(['method'])->class('flex flex-col gap-5') }}>
    {{ $slot }}
</form>
