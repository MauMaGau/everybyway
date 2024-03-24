<?php

namespace App\Livewire\Components;

use App\Models\Bimble;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class MapFilterTime extends Component
{
    public array $navTree       = [];
    public array $shownBimbles  = [];
    public array $hiddenBimbles = [];
    public array $debug;

    public function render(): View
    {
        if (empty($this->navTree)) {
            $bimbles = auth()->user()->bimbles()->havingPublicPings()->get();

            $this->shownBimbles = $bimbles->pluck('id')->toArray();

            $this->buildTree($bimbles);
        }

        return view('livewire.components.map-filter-time');
    }

    public function dayToggle(int $dayId, bool $active): void
    {

    }

    public function bimbleToggle(int $bimbleId, bool $active): void
    {
        if ($active) {
            $this->hideBimble($bimbleId);
        } else {
            $this->showBimble($bimbleId);
        }

        $bimble = Bimble::findOrFail($bimbleId);

        $this->navTree[$bimble->started_at->year]['months'][$bimble->started_at->month]['days'][$bimble->started_at->day]['bimbles'][$bimble->id]['active'] = !$active;

        $this->updateDay($this->navTree[$bimble->started_at->year]['months'][$bimble->started_at->month]['days'][$bimble->started_at->day]);

        $this->updateMonth($this->navTree[$bimble->started_at->year]['months'][$bimble->started_at->month]);

        $this->dispatch('bimbles-changed', shownBimbles: $this->shownBimbles, hiddenBimbles: $this->hiddenBimbles);
    }

    protected function updateDay(array &$day): void
    {
        $day['hasActiveBimbles'] = $this->dayHasActiveBimbles($day);

        if ($day['hasActiveBimbles']) {
            $this->hideInactiveBimbles($day['bimbles']);
        } else {
            $this->showBimbles($day['bimbles']);
        }
    }

    protected function updateMonth(array &$month): void
    {
        $month['hasActiveBimbles'] = $this->monthHasActiveBimbles($month);
        $month['hasActiveDays'] = $this->monthHasActiveDays($month);

        if ($month['hasActiveBimbles'] || $month['hasActiveDays']) {
            $this->hideInactiveDaysBimbles($month['days']);
        }
    }

    protected function showBimbles(array $bimbles): void
    {
        foreach ($bimbles as $bimble) {
            $this->showBimble($bimble['id']);
        }
    }

    protected function showBimble(int $bimbleId): void
    {
        foreach ($this->hiddenBimbles as $key => $value) {
            if ($bimbleId == $value) {
                unset($this->hiddenBimbles[$key]);
            }
        }
        if (!in_array($bimbleId, $this->shownBimbles)) {
            $this->shownBimbles[] = $bimbleId;
        }
    }

    protected function hideInactiveDaysBimbles(array $days): void
    {
        foreach ($days as $day) {
            if (!$day['active']) {
                $this->hideInactiveBimbles($day['bimbles']);
            }
        }
    }

    protected function hideInactiveBimbles(array $bimbles): void
    {
        foreach ($bimbles as $bimble) {
            if (!$bimble['active']) {
                $this->hideBimble($bimble['id']);
            }
        }
    }

    protected function hideBimbles(array $bimbles): void
    {
        foreach ($bimbles as $bimble) {
            $this->hideBimble($bimble['id']);
        }
    }

    protected function hideBimble(int $bimbleId): void
    {
        foreach ($this->shownBimbles as $key => $value) {
            if ($bimbleId == $value) {
                unset($this->shownBimbles[$key]);
            }
        }
        if (!in_array($bimbleId, $this->hiddenBimbles)) {
            $this->hiddenBimbles[] = $bimbleId;
        }
    }

    protected function monthHasActiveDays($month): bool
    {
        foreach ($month['days'] as $day) {
            if ($day['active']) {
                return true;
            }
        }
        return false;
    }

    protected function monthHasActiveBimbles($month): bool
    {
        foreach ($month['days'] as $day) {
            foreach ($day['bimbles'] as $bimble) {
                if ($bimble['active']) {
                    return true;
                }
            }
        }
        return false;
    }

    protected function dayHasActiveBimbles($day): bool
    {
        foreach ($day['bimbles'] as $bimble) {
            if ($bimble['active']) {
                return true;
            }
        }
        return false;
    }

    protected function buildTree($bimbles): void
    {
        $bimbles->each(function (Bimble $bimble) {
            // Years
            if (!array_key_exists($bimble->started_at->year, $this->navTree)) {
                $this->navTree[$bimble->started_at->year] = [
                    'id' => $bimble->started_at->year,
                    'active' => false,
                    'hasActiveMonths' => false,
                    'hasActiveDays' => false,
                    'hasActiveBimbles' => false,
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
                    'hasActiveDays' => false,
                    'hasActiveBimbles' => false,
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

    //    protected function filterBimbles(): array
    //    {
    //        $filteredBimbles = [];
    //
    //        // Set bimbleIds and hasActiveBimbles properties in navTree
    //        foreach($this->navTree as $yearKey => $year) {
    //            foreach ($year['months'] as $monthKey => $month) {
    //                $this->navTree[$yearKey]['months'][$monthKey]['hasActiveBimbles'] = false;
    //                $this->navTree[$yearKey]['months'][$monthKey]['hasActiveDays'] = true;
    //                foreach ($month['days'] as $dayKey => $day) {
    //                    $this->navTree[$yearKey]['months'][$monthKey]['days'][$dayKey]['hasActiveBimbles'] = false;
    //                    foreach ($day['bimbles'] as $bimble) {
    //                        $this->navTree[$yearKey]['months'][$monthKey]['days'][$dayKey]['bimbleIds'][$bimble['id']] = $bimble['id'];
    //                        $this->navTree[$yearKey]['months'][$monthKey]['bimbleIds'][$bimble['id']] = $bimble['id'];
    //                        $month['bimbleIds'][] = $bimble['id'];
    //
    //                        if ($bimble['active'] && $day['active'] && $month['active'] && $year['active']) {
    //                            $filteredBimbles[] = Bimble::find($bimble['id']);
    //                            $this->navTree[$yearKey]['months'][$monthKey]['days'][$dayKey]['hasActiveBimbles'] = true;
    //                            $this->navTree[$yearKey]['months'][$monthKey]['hasActiveDays'] = false;
    //                            $this->navTree[$yearKey]['months'][$monthKey]['hasActiveBimbles'] = true;
    //                        }
    //                    }
    //                }
    //            }
    //        }
    //
    //        // Add correct bimbles to map based on previously set navTree properties
    //        foreach($this->navTree as $year) {
    //            foreach ($year['months'] as $month) {
    //                foreach ($month['days'] as $day) {
    //                    if ($day['hasActiveBimbles'] === false && $day['active']) {
    //                        $month['hasActiveBimbles'] = true;
    //                        $filteredBimbles = array_merge($filteredBimbles, Bimble::whereIn('id', $day['bimbleIds'])->get()->toArray());
    //                    }
    //                }
    //                if ($month['hasActiveBimbles'] === false && $month['active'] && $month['hasActiveDays']) {
    //                    $filteredBimbles = array_merge($filteredBimbles, Bimble::whereIn('id', $month['bimbleIds'])->get()->toArray());
    //                    foreach ($day['bimbles'] as $bimble) {
    //                        $bimble['active'] = true;
    //                    }
    //                }
    //            }
    //        }
    //
    //        return $filteredBimbles;
    //    }
}
