@php
    $studioTheme = app(\GabrielBMorais\Studio\UI\Theme\ThemeManager::class)->resolve($attributes->get('theme'));
    $studioTitle = $attributes->get('title', config('app.name', 'Studio'));
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-studio-theme="{{ $studioTheme }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="studio-layout" content="{{ $attributes->get('name', 'app') }}">
    <title>{{ $studioTitle }}</title>

    @if(file_exists(public_path('hot')) || file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body data-studio-component="layout" class="min-h-screen bg-studio-background font-sans text-studio-foreground antialiased">
    {{ $slot }}
</body>
</html>
