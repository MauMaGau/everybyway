<?php

namespace App\Livewire\Map;

use App\Models\Bimble;
use App\Models\Ping;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class Map extends Component
{
    public Collection $pings;
    public Collection $bimbles;

//    #[On('bimbles-changed')]
//    public function updateBimbles(array $bimbles)
//    {
//        $this->bimbles = collect($bimbles);
//        $this->render();
//    }

    public function render()
    {
        $this->bimbles = Bimble::withWhereHas('pings', function ($query) {
            $query->where('is_home_area', false);
        })->get();

        if (Auth::guest()) {
            return view('livewire.map.map')->layout('layouts.guest')->section('slot');
        }

        return view('livewire.map.map')->layout('layouts.app')->section('slot');
    }
}
