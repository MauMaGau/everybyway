<?php

namespace App\Livewire\Layout;

use App\Livewire\Actions\Logout;
use App\Models\Bimble;
use Illuminate\View\View;
use Livewire\Component;

class SidenavAuth extends Component
{
    public function render(): View
    {
        return view('livewire.layout.sidenav-auth');
    }

}
