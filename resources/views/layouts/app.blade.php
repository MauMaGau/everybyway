<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('layouts.partials.head')

    <body>
        <div class="h-screen flex flex-col">
            {{ $slot }}
        </div>
    </body>
</html>
