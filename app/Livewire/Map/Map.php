<?php

namespace App\Livewire\Map;

use App\Models\Ping;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Map extends Component
{
    public Collection $pings;

    public function render()
    {
        $this->pings = Ping::where('is_home_area', false)->get();

        if (Auth::guest()) {
            return view('livewire.map.map')->layout('layouts.guest')->section('slot');
        }

        return view('livewire.map.map')->layout('layouts.app')->section('slot');
    }
}
