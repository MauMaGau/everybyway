<x-app-layout>
    @if (auth()->guest())
        <livewire:layout.guest-navigation/>
    @else
        <livewire:layout.navigation/>
    @endif
    <div class="flex flex-row h-full">
        @if (auth()->check())
            <livewire:layout.sidenav/>
        @endif
        <livewire:map.map/>
    </div>
</x-app-layout>
