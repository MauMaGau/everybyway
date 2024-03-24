<?php

namespace App\Livewire\Components;

use App\Services\TreeFilter\FilterItem;
use Illuminate\View\View;
use Livewire\Component;

class TreeFilter extends Component
{
    public FilterItem $filterItem;

    public function render(): View
    {
        return view('livewire.components.tree-filter');
    }

    public function toggleItem(): void
    {
        $this->filterItem->active = !$this->filterItem->active;
    }
}
