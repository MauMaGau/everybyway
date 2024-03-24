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

    public function dayToggle(string $dayId, bool $active): void
    {
        $this->setTreeItem($dayId, 'day', ['active' => !$active]);

        // show

        $this->updateDay($dayId);
        $this->updateMonth($dayId);
        // If Active && !hasActiveBimbles: showBimbles, update Month, update Year
        // If Active && hasActiveBimbles: update Month, update Year
        // if !Active: update Month, update Year
    }

    public function bimbleToggle(array $bimbleData): void
    {
        if ($bimbleData['active']) {
            $this->hideBimble($bimbleData['model_id']);
        } else {
            $this->showBimble($bimbleData['model_id']);
        }

        $bimble = Bimble::findOrFail($bimbleData['model_id']);

        $this->setTreeItem($bimbleData['id'], 'bimble', ['active' => !$bimbleData['active']]);

        $this->updateDay($this->makeTreeId($bimble->started_at->year, $bimble->started_at->month, $bimble->started_at->day));

        $this->updateMonth($this->makeTreeId($bimble->started_at->year, $bimble->started_at->month));

        $this->dispatch('bimbles-changed', shownBimbles: $this->shownBimbles, hiddenBimbles: $this->hiddenBimbles);
    }

    protected function updateDay(string $dayId): void
    {
        $day = $this->getTreeItem($dayId, 'day');

        $hasActiveBimbles = $this->dayHasActiveBimbles($day);

        $this->setTreeItem($dayId, 'day', ['hasActiveBimbles' => $hasActiveBimbles]);

        if ($hasActiveBimbles) {
            $this->hideInactiveBimbles($day['bimbles']);
        } else {
            $this->showBimbles($day['bimbles']);
        }
    }

    protected function updateMonth(string $monthId): void
    {
        $month = $this->getTreeItem($monthId, 'month');

        $hasActiveBimbles = $this->monthHasActiveBimbles($month);
        $hasActiveDays = $this->monthHasActiveDays($month);

        $this->setTreeItem($monthId, 'month', ['hasActiveBimbles' => $hasActiveBimbles]);
        $this->setTreeItem($monthId, 'month', ['hasActiveDays' => $hasActiveDays]);

        if ($hasActiveDays) {
            $this->hideInactiveDaysBimbles($month['days']);
        } else {
            $this->showDaysBimbles($month['days']);
        }

        foreach ($month['days'] as $day) {
            if ($hasActiveBimbles) {
                $this->hideInactiveBimbles($day['bimbles']);
            } else {
                $this->showBimbles($day['bimbles']);
            }
        }
    }

    protected function showDaysBimbles(array $days): void
    {
        foreach ($days as $day) {
            $this->showBimbles($day['bimbles']);
        }
    }

    protected function showBimbles(array $bimbles): void
    {
        foreach ($bimbles as $bimble) {
            $this->showBimble($bimble['model_id']);
        }
    }

    protected function showBimble(string $bimbleId): void
    {
        foreach ($this->hiddenBimbles as $key => $value) {
            if ($bimbleId == $value) {
                unset($this->hiddenBimbles[$key]);
            }
        }
        if (!in_array($bimbleId, $this->shownBimbles)) {
            $this->shownBimbles[] = (int)$bimbleId;
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
                $this->hideBimble($bimble['model_id']);
            }
        }
    }

    protected function hideBimbles(array $bimbles): void
    {
        foreach ($bimbles as $bimble) {
            $this->hideBimble($bimble['model_id']);
        }
    }

    protected function hideBimble(string $bimbleId): void
    {
        foreach ($this->shownBimbles as $key => $value) {
            if ($bimbleId == $value) {
                unset($this->shownBimbles[$key]);
            }
        }
        if (!in_array($bimbleId, $this->hiddenBimbles)) {
            $this->hiddenBimbles[] = (int)$bimbleId;
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
            $yearId = $this->makeTreeId($bimble->started_at->year);
            if (!$this->checkTreeItemExists($yearId, 'year')) {
                $this->setTreeItem($yearId, 'year', [
                    'id' => $yearId,
                    'active' => false,
                    'hasActiveMonths' => false,
                    'hasActiveDays' => false,
                    'hasActiveBimbles' => false,
                    'months' => [],
                ]);
            }

            // Months
            $monthId = $this->makeTreeId($bimble->started_at->year, $bimble->started_at->month);
            if (!$this->checkTreeItemExists($monthId, 'month')) {
                $this->setTreeItem($monthId, 'month', [
                    'id' => $monthId,
                    'text' => $bimble->started_at->monthName,
                    'active' => false,
                    'hasActiveDays' => false,
                    'hasActiveBimbles' => false,
                    'days' => [],
                ]);
            }

            // Days initiation
            $dayId = $this->makeTreeId($bimble->started_at->year, $bimble->started_at->month, $bimble->started_at->day);
            if (!$this->checkTreeItemExists($dayId, 'day')) {
                $this->setTreeItem($dayId, 'day', [
                    'id' => $dayId,
                    'text' => $bimble->started_at->format('jS'),
                    'active' => false,
                    'hasActiveBimbles' => false,
                    'bimbles' => []
                ]);
            }

            $bimbleId = $this->makeTreeId($bimble->started_at->year, $bimble->started_at->month, $bimble->started_at->day, $bimble->id);
            if (!$this->checkTreeItemExists($bimbleId, 'bimble')) {
                $this->setTreeItem($bimbleId, 'bimble', [
                    'id' => $bimbleId,
                    'model_id' => $bimble->id,
                    'text' => $bimble->started_at->format('H:i') . ' - ' . $bimble->ended_at->format('H:i'),
                    'active' => false,
                ]);
            }
        });
    }

    protected function makeTreeId(int $year, int $month = null, int $day = null, int $bimbleId = null): string
    {
        $parts = [$year];
        if ($month) {
            $parts[] = $month;
        }
        if ($day) {
            $parts[] = $day;
        }
        if ($bimbleId) {
            $parts[] = $bimbleId;
        }

        return implode('|', $parts);
    }

    protected function checkTreeItemExists(string $itemId, string $itemType): bool
    {
        $itemIdParts = explode('|', $itemId);

        if ($itemType === 'year') {
            return isset($this->navTree[$itemIdParts[0]]);
        }

        if ($itemType === 'month') {
            return isset($this->navTree[$itemIdParts[0]]['months'][$itemIdParts[1]]);
        }

        if ($itemType === 'day') {
            return isset($this->navTree[$itemIdParts[0]]['months'][$itemIdParts[1]]['days'][$itemIdParts[2]]);
        }

        if ($itemType === 'bimble') {
            return isset($this->navTree[$itemIdParts[0]]['months'][$itemIdParts[1]]['days'][$itemIdParts[2]]['bimbles'][$itemIdParts[3]]);
        }

        return false;
    }

    protected function setTreeItem(string $itemId, string $itemType, array $data): void
    {
        $itemIdParts = explode('|', $itemId);

        if ($itemType === 'year') {
            foreach ($data as $key => $value) {
                $this->navTree[$itemIdParts[0]][$key] = $value;
            }
        }

        if ($itemType === 'month') {
            foreach ($data as $key => $value) {
                $this->navTree[$itemIdParts[0]]['months'][$itemIdParts[1]][$key] = $value;
            }
        }

        if ($itemType === 'day') {
            foreach ($data as $key => $value) {
                $this->navTree[$itemIdParts[0]]['months'][$itemIdParts[1]]['days'][$itemIdParts[2]][$key] = $value;
            }
        }

        if ($itemType === 'bimble') {
            foreach ($data as $key => $value) {
                $this->navTree[$itemIdParts[0]]['months'][$itemIdParts[1]]['days'][$itemIdParts[2]]['bimbles'][$itemIdParts[3]][$key] = $value;
            }
        }
    }

    protected function getTreeItem(string $itemId, string $itemType): ?array
    {
        $arr = $this->splitItemId($itemId);

        if ($itemType === 'year') {
            return $this->navTree[$arr['year']];
        }

        if ($itemType === 'month') {
            return $this->navTree[$arr['year']]['months'][$arr['month']];
        }

        if ($itemType === 'day') {
            return $this->navTree[$arr['year']]['months'][$arr['month']]['days'][$arr['day']];
        }

        if ($itemType === 'bimble') {
            return $this->navTree[$arr['year']]['months'][$arr['month']]['days'][$arr['day']]['bimbles'][$arr['bimble']];
        }

        return null;
    }

    protected function splitItemId(string $itemId): array
    {
        $itemIdParts = explode('|', $itemId);
        $keys = array_slice(['year', 'month', 'day', 'bimble'], -0, count($itemIdParts));

        return array_combine($keys, $itemIdParts);
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
