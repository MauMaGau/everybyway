<?php

namespace App\Livewire\Layout;

use Illuminate\View\View;
use Livewire\Component;

class SidenavGuest extends Component
{
    public bool $showLogin = false;
    public bool $showRegister = false;

    public function showLoginForm():void
    {
        $this->showLogin = true;
        $this->showRegister = false;
    }

    public function showRegisterForm():void
    {
        $this->showRegister = true;
        $this->showLogin = false;
    }

    public function render(): View
    {
        return view('livewire.layout.sidenav-guest');
    }
}
