<?php

use Livewire\Component;

class RegisterForm extends Component
?>
<div>
    <form wire:submit="register">
        <label for="email">Email</label>
        <input wire:model="email" id="email" type="email" name="email" required autofocus autocomplete="username"/>
        @error('email')<p class="error">{{ $message }}</p>@enderror

        <label for="password">Password</label>
        <input wire:model="password" type="password" name="password" required autofocus autocomplete="current-password"/>
        @error('password')<p class="error">{{ $message }}</p>@enderror

        <button type="submit">Register</button>
    </form>

    Already have an account?
    <a wire:click="$parent.toggleLogin(true)">
        Login
    </a>
</div>
