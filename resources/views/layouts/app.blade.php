<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? ($settings->site_name ?? 'New World Nursery') }}</title>
    <meta name="description" content="{{ $settings->hero_subtitle ?? 'A happy place to learn and grow' }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-800 bg-nursery-cream antialiased">
    @yield('content')
</body>
</html>
