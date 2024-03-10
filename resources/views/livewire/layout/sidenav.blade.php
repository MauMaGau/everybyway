@php use Carbon\Carbon; @endphp
<nav class="h-full w-[300px] bg-white border border-4-1 border-gray-100 pl-3">
    <h3>Bimbles</h3>
    <ul class="pl-3">
        @foreach($months as $month)
            <li wire:key="{{ $month['id'] }}">
                <x-nav-link wire:click="filter('{{ $month['number'] }}')">
                    {{ $month['text'] }}
                </x-nav-link>
                <ul class="pl-3">
                    @foreach($month['days'] as $day)
                        <li wire:key="{{ $day['date'] }}">
                            <x-nav-link wire:click="filter('{{ $month['number'] }}', '{{$day['date'] }}')">
                                {{ $day['date']->format('jS') }}
                            </x-nav-link>
                            <ul class="pl-3">
                                @foreach($day['bimbles'] as $bimble)
                                    <li wire:key="{{ $bimble->started_at }}">
                                        <x-nav-link wire:click="filter('{{ $month['number'] }}', '{{$day['date'] }}', '{{ $bimble->started_at }}')">
                                            {{ $bimble->started_at->format('H:i') }} - {{ $bimble->ended_at->format('H:i') }}
                                        </x-nav-link>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
</nav>
