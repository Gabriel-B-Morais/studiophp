@php
    $name = (string) $attributes->get('name');
    $label = $attributes->get('label');
    $type = (string) $attributes->get('type', 'text');
    $inputAttributes = $attributes->except(['label']);
@endphp

<div data-studio-component="input">
    @if($label !== null)
        <label for="{{ $name }}">{{ $label }}</label>
    @endif

    <input id="{{ $name }}" type="{{ $type }}" {{ $inputAttributes }}>
</div>
