<?php

namespace App\Livewire\Components;

use App\Models\Bimble;
use Illuminate\View\View;
use Livewire\Component;

class MapFilterTime extends Component
{
    public array $navTree = [];
    public array $debug;

    public function render(): View
    {
        $bimbles = auth()->user()->bimbles()->havingPublicPings()->get();

        if (empty($this->navTree)) {
            $this->buildTree($bimbles);
        }

        $this->dispatch('bimbles-changed', bimbles: $this->filterBimbles());

        return view('livewire.components.map-filter-time');
    }

    protected function buildTree($bimbles): void
    {
        $bimbles->each(function (Bimble $bimble) {
            // Years
            if (!array_key_exists($bimble->started_at->year, $this->navTree)) {
                $this->navTree[$bimble->started_at->year] = [
                    'id' => $bimble->started_at->year,
                    'active' => false,
                ];
            }

            // Months initiation
            if (!array_key_exists('months', $this->navTree[$bimble->started_at->year])) {
                $this->navTree[$bimble->started_at->year]['months'] = [];
            }

            // Months
            if (!array_key_exists($bimble->started_at->month, $this->navTree[$bimble->started_at->year]['months'])) {
                $this->navTree[$bimble->started_at->year]['months'][$bimble->started_at->month] = [
                    'id' => $bimble->started_at->month,
                    'text' => $bimble->started_at->monthName,
                    'active' => false,
                    'hasActiveBimbles' => false,
                    'hasActiveDays' => false,
                    'bimbleIds' => [],
                ];
            }

            // Days initiation
            if (!array_key_exists('days', $this->navTree[$bimble->started_at->year]['months'][$bimble->started_at->month])) {
                $this->navTree[$bimble->started_at->year]['months'][$bimble->started_at->month]['days'] = [];
            }

            // Days
            if (!array_key_exists($bimble->started_at->day, $this->navTree[$bimble->started_at->year]['months'][$bimble->started_at->month]['days'])) {
                $this->navTree[$bimble->started_at->year]['months'][$bimble->started_at->month]['days'][$bimble->started_at->day] = [
                    'id' => $bimble->started_at->day,
                    'text' => $bimble->started_at->format('jS'),
                    'active' => false,
                    'hasActiveBimbles' => false,
                    'bimbleIds' => false,
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

        // Set bimbleIds and hasActiveBimbles properties in navTree
        foreach($this->navTree as $yearKey => $year) {
            foreach ($year['months'] as $monthKey => $month) {
                $this->navTree[$yearKey]['months'][$monthKey]['hasActiveBimbles'] = false;
                $this->navTree[$yearKey]['months'][$monthKey]['hasActiveDays'] = true;
                foreach ($month['days'] as $dayKey => $day) {
                    $this->navTree[$yearKey]['months'][$monthKey]['days'][$dayKey]['hasActiveBimbles'] = false;
                    foreach ($day['bimbles'] as $bimble) {
                        $this->navTree[$yearKey]['months'][$monthKey]['days'][$dayKey]['bimbleIds'][$bimble['id']] = $bimble['id'];
                        $this->navTree[$yearKey]['months'][$monthKey]['bimbleIds'][$bimble['id']] = $bimble['id'];
                        $month['bimbleIds'][] = $bimble['id'];

                        if ($bimble['active'] && $day['active'] && $month['active'] && $year['active']) {
                            $filteredBimbles[] = Bimble::find($bimble['id']);
                            $this->navTree[$yearKey]['months'][$monthKey]['days'][$dayKey]['hasActiveBimbles'] = true;
                            $this->navTree[$yearKey]['months'][$monthKey]['hasActiveDays'] = false;
                            $this->navTree[$yearKey]['months'][$monthKey]['hasActiveBimbles'] = true;
                        }
                    }
                }
            }
        }

        // Add correct bimbles to map based on previously set navTree properties
        foreach($this->navTree as $year) {
            foreach ($year['months'] as $month) {
                foreach ($month['days'] as $day) {
                    if ($day['hasActiveBimbles'] === false && $day['active']) {
                        $month['hasActiveBimbles'] = true;
                        $filteredBimbles = array_merge($filteredBimbles, Bimble::whereIn('id', $day['bimbleIds'])->get()->toArray());
                    }
                }
                if ($month['hasActiveBimbles'] === false && $month['active'] && $month['hasActiveDays']) {
                    $filteredBimbles = array_merge($filteredBimbles, Bimble::whereIn('id', $month['bimbleIds'])->get()->toArray());
                    foreach ($day['bimbles'] as $bimble) {
                        $bimble['active'] = true;
                    }
                }
            }
        }

        return $filteredBimbles;
    }
}
