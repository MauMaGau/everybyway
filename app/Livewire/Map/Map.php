<?php

namespace App\Livewire\Map;

use App\Models\Bimble;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Map extends Component
{
    public Collection $bimbles;

    public function render()
    {
        if (Auth::guest()) {
            $this->bimbles = Bimble::withPublicPings()->get();
            return view('livewire.map.map')->layout('layouts.guest')->section('slot');
        }

        $this->bimbles = auth()->user()->bimbles()->withPublicPings()->get();
        return view('livewire.map.map')->layout('layouts.app')->section('slot');
    }
}
