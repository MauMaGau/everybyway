<?php

namespace App\Livewire\Layout;

use App\Livewire\Actions\Logout;
use App\Models\Bimble;
use Illuminate\View\View;
use Livewire\Component;

class SidenavAuth extends Component
{
    //    public Collection $bimbles;
    public array $navTree = [];
    public array $debug;

    public function render(): View
    {
        $bimbles = auth()->user()->bimbles()->havingPublicPings()->get();

        if (empty($this->navTree)) {
            $this->buildTree($bimbles);
        }

        $this->dispatch('bimbles-changed', bimbles: $this->filterBimbles());

        return view('livewire.layout.sidenav-auth');
    }

    protected function buildTree($bimbles)
    {
        $bimbles->each(function (Bimble $bimble) {
            if (!array_key_exists($bimble->started_at->year, $this->navTree)) {
                $this->navTree[$bimble->started_at->year] = [
                    'id' => $bimble->started_at->year,
                    'active' => false,
                ];
            }

            if (!array_key_exists('months', $this->navTree[$bimble->started_at->year])) {
                $this->navTree[$bimble->started_at->year]['months'] = [];
            }

            if (!array_key_exists($bimble->started_at->month, $this->navTree[$bimble->started_at->year]['months'])) {
                $this->navTree[$bimble->started_at->year]['months'][$bimble->started_at->month] = [
                    'id' => $bimble->started_at->month,
                    'text' => $bimble->started_at->monthName,
                    'active' => false,
                ];
            }

            if (!array_key_exists('days', $this->navTree[$bimble->started_at->year]['months'][$bimble->started_at->month])) {
                $this->navTree[$bimble->started_at->year]['months'][$bimble->started_at->month]['days'] = [];
            }

            if (!array_key_exists($bimble->started_at->day, $this->navTree[$bimble->started_at->year]['months'][$bimble->started_at->month]['days'])) {
                $this->navTree[$bimble->started_at->year]['months'][$bimble->started_at->month]['days'][$bimble->started_at->day] = [
                    'id' => $bimble->started_at->day,
                    'text' => $bimble->started_at->format('jS'),
                    'active' => false,
                ];
            }

            if (!array_key_exists('bimbles', $this->navTree[$bimble->started_at->year]['months'][$bimble->started_at->month]['days'][$bimble->started_at->day])) {
                $this->navTree[$bimble->started_at->year]['months'][$bimble->started_at->month]['days'][$bimble->started_at->day]['bimbles'] = [];
            }

            if (!array_key_exists($bimble->id, $this->navTree[$bimble->started_at->year]['months'][$bimble->started_at->month]['days'][$bimble->started_at->day]['bimbles'])) {
                $this->navTree[$bimble->started_at->year]['months'][$bimble->started_at->month]['days'][$bimble->started_at->day]['bimbles'][$bimble->id] = [
                    'id' => $bimble->id,
                    'text' => $bimble->started_at->format('H:i') . ' - ' . $bimble->ended_at->format('H:i'),
                    'active' => false,
                ];
            }
        });
    }

    protected function filterBimbles(): array
    {
        $filteredBimbles = [];
        $bimblesActive = false;

        foreach($this->navTree as $year) {

            foreach ($year['months'] as $month) {
                $thisMonthHasActiveBimbles = false;
                $thisMonthHasActiveDays = false;

                foreach ($month['days'] as $day) {
                    $thisDayHasActiveBimbles = false;
                    $bimbleIdsForDay = [];

                    foreach ($day['bimbles'] as $bimble) {
                        $bimbleIdsForDay[] = $bimble['id'];
                        $bimbleIdsForMonth[] = $bimble['id'];
                        if ($bimble['active']) {
                            $filteredBimbles[] = Bimble::find($bimble['id']);
                            $bimblesActive = true;
                            $thisDayHasActiveBimbles = true;
                            $thisMonthHasActiveBimbles = true;
                        }
                    }

                    // TODO: Need to know if future bimbles are active
                    if (!$thisDayHasActiveBimbles && $day['active'] && !$bimblesActive) {
                        $thisMonthHasActiveDays = true;
                        $filteredBimbles = array_merge($filteredBimbles, Bimble::whereIn('id', $bimbleIdsForDay)->get()->toArray());
                    }
                }

                if (!$thisMonthHasActiveBimbles && !$thisMonthHasActiveDays && !$bimblesActive) {
                    $filteredBimbles = array_merge($filteredBimbles, Bimble::whereIn('id', $bimbleIdsForMonth)->get()->toArray());
                    foreach ($day['bimbles'] as $bimble) {
                        $bimble['active'] = true;
                    }
                }
            }
        }
        return $filteredBimbles;
    }
}
