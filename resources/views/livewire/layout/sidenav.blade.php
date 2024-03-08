@php use Carbon\Carbon; @endphp
<nav class="h-full w-[300px] bg-white border border-4-1 border-gray-100">
    <h3>Months</h3>
    <ul>
        @foreach($months as $month)
            <li>
                {{ $month['text'] }}
                <ul>
                    @foreach($month['days'] as $day)
                        <li>
                            {{ $day }}
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
</nav>
