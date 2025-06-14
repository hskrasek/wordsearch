<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('meta::manager')

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @routes
        @vite('resources/js/app.ts')
        <script defer data-domain="{{ config('app.url') }}" data-exclude="/api/*" src="https://plausible.io/js/plausible.js"></script>
    </head>
    <body class="font-sans antialiased h-full">
        @inertia
    </body>
</html>
