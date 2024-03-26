<nav class="sidenav">
    <x-sidenav-header></x-sidenav-header>

    @if ($showLogin)
        <livewire:components.login-form />
    @elseif ($showRegister)
        <livewire:components.register-form />
    @else
        <p class="actions">
            <a wire:click="showLoginForm()">
                Login
            </a> |
            <a wire:click="showRegisterForm()">
                Register
            </a>
        </p>
    @endif
</nav>
