<?php

namespace App\Livewire\Layout;

use App\Models\Bimble;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class Sidenav extends Component
{
    public Collection $bimbles;
    public array      $months;

    public function filter(int $month = null, string $day = null, string $time = null): void
    {
        if (!$month && !$day && !$time) {
            $this->dispatch('bimbles-changed', bimbles: $this->bimbles);
        }

        $filteredBimbles = $this->bimbles->filter(function(Bimble $bimble) use ($month, $day, $time) {
            if ($month) {
                if ($bimble->started_at->month !== $month) {
                    return false;
                }
            }

            if ($day) {
                if (!$bimble->started_at->isSameDay($day)) {
                    return false;
                }
            }

            if ($time) {
                if (!$bimble->started_at->isSameAs('Y-m-d H:i:s', $time)) {
                    return false;
                }
            }

            return true;
        });

        $this->dispatch('bimbles-changed', bimbles: $filteredBimbles);
    }

    public function render(): View
    {
        $this->bimbles = auth()->user()->bimbles;
        $this->months = [];

        for($monthId=0; $monthId < 10; $monthId++) {
            $month = Carbon::now()->subMonths($monthId);

            $bimbles = $this->bimbles->filter(function($bimble) use ($month) {
                return $bimble->started_at->month === $month->month;
            });

            $days = [];

            $bimbles->each(function(Bimble $bimble) use (&$days) {
                $days[$bimble->started_at->day]['date'] = $bimble->started_at->setTime(0, 0);
                $days[$bimble->started_at->day]['bimbles'][] = $bimble;
            });

            if ($bimbles->isEmpty()) {
                continue;
            }

            $this->months[] = [
                'id' => $monthId,
                'text' => $month->monthName,
                'number' => $month->month,
                'days' => $days,
            ];


        }

        return view('livewire.layout.sidenav');
    }
}
