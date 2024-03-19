<?php

namespace App\Livewire\Layout;

use Illuminate\View\View;
use Livewire\Component;

class SidenavGuest extends Component
{
    public bool $showLogin = true;

    public function toggleLogin(bool $showLogin):void
    {
        $this->showLogin = $showLogin;
    }

    public function render(): View
    {
        return view('livewire.layout.sidenav-guest');
    }
}
