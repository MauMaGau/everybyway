<div>
    <form wire:submit="register">
        <label for="name">Username</label>
        <input wire:model="name" id="name" type="name" name="name" required autofocus autocomplete="username"/>
        @error('name')<p class="error">{{ $message }}</p>@enderror

        <label for="email">Email</label>
        <input wire:model="email" id="email" type="email" name="email" required autocomplete="username"/>
        @error('email')<p class="error">{{ $message }}</p>@enderror

        <label for="password">Password</label>
        <input wire:model="password" type="password" name="password" required autocomplete="current-password"/>
        @error('password')<p class="error">{{ $message }}</p>@enderror

        <button type="submit">Register</button>
    </form>

    <p>Already have an account?</p>
    <a wire:click="$parent.toggleLogin(true)">
        Login
    </a>
</div>
