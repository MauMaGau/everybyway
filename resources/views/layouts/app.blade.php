<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('layouts.partials.head')

    <body class="h-screen">
        {{ $slot }}
    </body>
</html>
