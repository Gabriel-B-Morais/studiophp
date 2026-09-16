<form data-studio-component="form" method="{{ $attributes->get('method', 'POST') }}" {{ $attributes->except(['method']) }}>
    {{ $slot }}
</form>
