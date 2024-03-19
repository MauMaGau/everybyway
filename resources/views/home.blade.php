<x-app-layout>
    @if (auth()->check())
        <livewire:layout.sidenav-auth/>
    @else
        <livewire:layout.sidenav-guest/>
    @endif
    <livewire:map.map/>
</x-app-layout>
