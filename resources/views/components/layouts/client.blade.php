<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PortFlow Connect | Client Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-900">
    <main>
        {{ $slot }}
    </main>
    @livewireScripts
</body>
</html>
