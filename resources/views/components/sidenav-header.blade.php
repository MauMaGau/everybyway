<header>
    <h1>
        @if ($slot->isEmpty())
            EveryByway
        @else
            {{ $slot }}
        @endif
    </h1>

    <small>Travel each and every byway</small>
</header>
