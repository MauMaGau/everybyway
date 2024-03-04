<?php

namespace App\Livewire;

use App\Models\Ping;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Map extends Component
{
    public Collection $pings;

    public function render()
    {
        $this->pings = Ping::where('is_home_area', false)->get();

        return view('livewire.map');
    }
}
