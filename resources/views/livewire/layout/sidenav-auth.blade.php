@php use Carbon\Carbon; @endphp
<nav class="sidenav">
    <x-sidenav-header></x-sidenav-header>
    <ul>
        @foreach($months as $month)
            <li wire:key="{{ $month['id'] }}">
                <a wire:click="filter('{{ $month['number'] }}')">
                    {{ $month['text'] }}
                </a>
                <ul class="pl-3">
                    @foreach($month['days'] as $day)
                        <li wire:key="{{ $day['date'] }}">
                            <a wire:click="filter('{{ $month['number'] }}', '{{$day['date'] }}')">
                                {{ $day['date']->format('jS') }}
                            </a>
                            <ul class="pl-3">
                                @foreach($day['bimbles'] as $bimble)
                                    <li wire:key="{{ $bimble->started_at }}">
                                        <a wire:click="filter('{{ $month['number'] }}', '{{$day['date'] }}', '{{ $bimble->started_at }}')">
                                            {{ $bimble->started_at->format('H:i') }} - {{ $bimble->ended_at->format('H:i') }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>

    <button wire:click="logout" class="w-full text-start">Log out</button>
    <livewire:components.logout-button/>
</nav>
