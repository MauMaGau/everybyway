@php use Carbon\Carbon; @endphp
<nav class="sidenav">

    <x-sidenav-header></x-sidenav-header>

    <ul
        x-data="{ expanded: false }">
        @foreach($months as $month)
            <li
                wire:key="{{ $month['id'] }}"
                x-data="{ expanded: false }">
                <a
                    wire:click="filter('{{ $month['number'] }}')"
                    x-on:click="expanded = ! expanded">
                    {{ $month['text'] }}
                </a>
                <ul
                    x-show="expanded"
                    x-transition>
                    @foreach($month['days'] as $day)
                        <li
                            wire:key="{{ $day['date'] }}"
                            x-data="{ expanded: false }">
                            <a
                                wire:click="filter('{{ $month['number'] }}', '{{$day['date'] }}')"
                                x-on:click="expanded = ! expanded">
                                {{ $day['date']->format('jS') }}
                            </a>
                            <ul
                                x-show="expanded">
                                @foreach($day['bimbles'] as $bimble)
                                    <li
                                        wire:key="{{ $bimble->started_at }}">
                                        <a
                                            wire:click="filter('{{ $month['number'] }}', '{{$day['date'] }}', '{{ $bimble->started_at }}')">
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

    <livewire:components.logout-button/>
</nav>
