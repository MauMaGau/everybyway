@php use Carbon\Carbon; @endphp
<nav class="h-full w-[300px] bg-white border border-4-1 border-gray-100 pl-3">
    <h3>Months</h3>
    <ul class="pl-3">
        @foreach($months as $month)
            <li wire:key="{{ $month['id'] }}">
                {{ $month['text'] }}
                <ul class="pl-3">
                    @foreach($month['days'] as $day)
                        <li wire:key="{{ $day['date'] }}">
                            {{ $day['date']->format('d-m-Y') }}
                            <ul class="pl-3">
                                @foreach($day['bimbles'] as $bimble)
                                    <li wire:click="filter('{{ $month['number'] }}', '{{$day['date'] }}', '{{ $bimble->started_at }}')" wire:key="{{ $bimble->started_at }}">
                                        {{ $bimble->started_at->format('H:i') }}
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
