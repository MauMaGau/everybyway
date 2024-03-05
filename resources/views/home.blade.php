<x-app-layout>
    @if (auth()->guest())
        <livewire:layout.guest-navigation/>
    @else
        <livewire:layout.navigation/>
    @endif
    <livewire:map.map/>
</x-app-layout>
