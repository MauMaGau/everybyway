<?php

namespace App\Livewire\Components;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Component;

class RegisterForm extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                Rule::unique('users'),
            ],
            'email' => [
                'required',
                'string',
                'email',
                Rule::unique('users'),
            ],
            'password' => [
                'required',
                'string',
                env('local')? '': Rules\Password::min(4)->uncompromised(),
            ]
        ];
    }

    public function messages(): array
    {
        return [];
    }

    public function register(): void
    {
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        $this->redirect('/', navigate: true);
    }

    public function render()
    {
        return view('livewire.components.register-form');
    }
}
