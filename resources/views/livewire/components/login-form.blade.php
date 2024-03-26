<?php

use Livewire\Component;

class LoginForm extends Component
?>
<div>
    <form wire:submit="login">
        <fieldset>
            <label for="email">Email</label>
            <input wire:model="email" id="email" type="email" name="email" required autofocus autocomplete="username"/>
            @error('email')<p class="error">{{ $message }}</p>@enderror
        </fieldset>

        <fieldset>
            <label for="password">Password</label>
            <input wire:model="password" type="password" name="password" required autofocus autocomplete="current-password"/>
            @error('password')<p class="error">{{ $message }}</p>@enderror
        </fieldset>
        <button type="submit">Log in</button>
    </form>

    <p class="footer">
        Don't have an account?
        <a wire:click="$parent.showRegisterForm()">
            Register
        </a>
    </p>
</div>
