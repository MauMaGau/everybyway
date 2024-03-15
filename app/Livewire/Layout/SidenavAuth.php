<?php

namespace App\Livewire\Layout;

use App\Livewire\Actions\Logout;
use App\Models\Bimble;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class SidenavAuth extends Component
{
    public Collection $bimbles;
    public array $months;

    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }

    public function render(): View
    {
        $this->bimbles = auth()->user()->bimbles()->havingPublicPings()->get();
        $filteredBimbles = []; // bimbles for the map js

        for ($monthId = 0; $monthId < 10; $monthId++) {
            $monthDate = Carbon::now()->subMonths($monthId);

            $bimbles = $this->bimbles->filter(function ($bimble) use ($monthDate) {
                return $bimble->started_at->month === $monthDate->month;
            });

            $days = $this->months[$monthId]['days'] ?? [];

            $bimbles->each(function (Bimble $bimble) use (&$days, $monthId, &$filteredBimbles) {
                $dayId = $bimble->started_at->day;

                $days[$dayId]['id'] = $bimble->started_at->day;
                $days[$dayId]['date_display'] = $bimble->started_at->format('jS');
                $days[$dayId]['active'] = $days[$dayId]['active'] ?? false;

                $bimble->started_at_display = $bimble->started_at->format('H:i');
                $bimble->ended_at_display = $bimble->ended_at->format('H:i');
                $bimble->active = $days[$dayId]['bimbles'][$bimble->id]->active ?? false;

                $days[$dayId]['bimbles'][$bimble->id] = $bimble->toArray();

//                if ($bimble->active) {
//                    $filteredBimbles[] = $bimble;
//                } elseif ($days[$dayId]['active'] && ) // TODO: If no siblings active, all bimbles in day should be active
            });

            if ($bimbles->isEmpty()) {
                continue;
            }

            $month = $this->months[$monthId] ?? [];
            $this->months[$monthId]['id'] = $monthId;
            $this->months[$monthId]['text'] = $monthDate->monthName;
            $this->months[$monthId]['number'] = $monthDate->month;
            $this->months[$monthId]['days'] = $days;
            $this->months[$monthId]['active'] = $month['active'] ?? false;
        }

        $this->dispatch('bimbles-changed', bimbles: $filteredBimbles);

        return view('livewire.layout.sidenav-auth');
    }
}
