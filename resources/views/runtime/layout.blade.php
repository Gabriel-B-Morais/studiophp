<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="studio-layout" content="{{ $attributes->get('name', 'app') }}">
    <title>{{ $attributes->get('title', config('app.name', 'Studio')) }}</title>
</head>
<body data-studio-component="layout">
    {{ $slot }}
</body>
</html>
