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
    public array $showing;

    public function filter(int $month = null, string $day = null, string $time = null): void
    {
        if (!$month && !$day && !$time) {
            $this->dispatch('bimbles-changed', bimbles: $this->bimbles);
        }

        $filteredBimbles = $this->bimbles->filter(function (Bimble $bimble) use ($month, $day, $time) {
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

    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }

    public function render(): View
    {
        $this->bimbles = auth()->user()->bimbles()->havingPublicPings()->get();

        $this->months = [];

        for ($monthId = 0; $monthId < 10; $monthId++) {
            $month = Carbon::now()->subMonths($monthId);

            $bimbles = $this->bimbles->filter(function ($bimble) use ($month) {
                return $bimble->started_at->month === $month->month;
            });

            $days = [];

            $bimbles->each(function (Bimble $bimble) use (&$days, $monthId) {
                $days[$bimble->started_at->day]['id'] = $bimble->started_at->day;
                $days[$bimble->started_at->day]['date_display'] = $bimble->started_at->format('jS');
                $days[$bimble->started_at->day]['active'] = false;

                $bimble->started_at_display = $bimble->started_at->format('H:i');
                $bimble->ended_at_display = $bimble->ended_at->format('H:i');

                $this->showing[$monthId]['days'][$bimble->started_at->day]['bimbles'][$bimble->id] = ['active' => $this->showing[$monthId]['days'][$bimble->started_at->day]['bimbles'][$bimble->id] ?? false];
                $this->showing[$monthId]['days'][$bimble->started_at->day]['active'] = $this->showing[$monthId]['days'][$bimble->started_at->day]['active'] ?? false;
                $this->showing[$monthId]['active'] = $this->showing[$monthId]['active'] ?? false;

                $days[$bimble->started_at->day]['bimbles'][$bimble->started_at->toDateTimeString()] = $bimble->toArray();
            });

            if ($bimbles->isEmpty()) {
                continue;
            }

            $this->months[$monthId] = [
                'id' => $monthId,
                'text' => $month->monthName,
                'number' => $month->month,
                'days' => $days,
            ];
        }

        return view('livewire.layout.sidenav-auth');
    }
}
