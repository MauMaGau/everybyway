<div>
    <form wire:submit="register">
        <fieldset>
            <label for="name">Pick a username</label>
            <input wire:model="name" id="name" type="name" name="name" required autofocus autocomplete="username"/>
            <p>This might end up being visible to other users</p>
            @error('name')<p class="error">{{ $message }}</p>@enderror
        </fieldset>

        <fieldset>
            <label for="email">What's your email?</label>
            <input wire:model="email" id="email" type="email" name="email" required autocomplete="username"/>
            <p>Never shared, no spam. Just for logging in and in case we need to contact you.</p>
            @error('email')<p class="error">{{ $message }}</p>@enderror
        </fieldset>

        <fieldset>
            <label for="password">Choose a password</label>
            <input wire:model="password" type="password" name="password" required autocomplete="current-password"/>
            <p>Choose something secure. We won't allow common passwords.</p>
            @error('password')<p class="error">{{ $message }}</p>@enderror
        </fieldset>

        <button type="submit">Register</button>
    </form>

    <p class="footer">Already have an account?
        <a wire:click="$parent.showLoginForm()">
            Login
        </a>
    </p>
</div>
