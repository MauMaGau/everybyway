<nav class="sidenav">
    <x-sidenav-header></x-sidenav-header>

    @if ($showLogin)
        <livewire:components.login-form />
    @else
        <livewire:components.register-form />
    @endif
</nav>
