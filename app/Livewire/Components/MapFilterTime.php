<?php

namespace App\Livewire\Components;

use App\Models\Bimble;
use App\Services\TreeFilter\FilterItem;
use App\Services\TreeFilter\TreeFilter;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class MapFilterTime extends Component
{
    public TreeFilter $treeFilter;
    public FilterItem $rootFilterItem;
    public array $shownBimbles  = [];
    public array $hiddenBimbles = [];
    public array $debug;

    public function render(): View
    {
        $this->treeFilter = new TreeFilter();

        if (empty($this->navTree)) {
            $bimbles = auth()->user()->bimbles()->havingPublicPings()->get();

            $this->shownBimbles = $bimbles->pluck('id')->toArray();

            $this->buildTree($bimbles);
        }

        return view('livewire.components.map-filter-time');
    }

    public function buildTree(Collection $bimbles)
    {
        $bimbles->each(function (Bimble $bimble) {
            $this->rootFilterItem = $this->treeFilter->createOrUpdateItem('.', collect()); // create a root FilterItem for recursion
            $yearFilterItem = $this->treeFilter->createOrUpdateItem($bimble->started_at->year, collect($bimble), $this->rootFilterItem); // create year item
            $monthFilterItem = $this->treeFilter->createOrUpdateItem($bimble->started_at->month, collect($bimble), $yearFilterItem); // create month item
            $dayFilterItem = $this->treeFilter->createOrUpdateItem($bimble->started_at->day, collect($bimble), $monthFilterItem); // create day item
            $bimbleFilterItem = $this->treeFilter->createOrUpdateItem($bimble->started_at->format('H:i') . ' - ' . $bimble->ended_at->format('H:i'), collect($bimble), $dayFilterItem); // create day item
        });
    }
}
