<?php

namespace App\Livewire;

use App\Models\Ping;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Map extends Component
{
    public Collection $pings;
    public int $x = 1;
    public function render()
    {
        $this->pings = Ping::orderBy('created_at', 'DESC')->take(5)->get();

        return view('livewire.map');
    }
}