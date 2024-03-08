<?php

namespace App\Livewire\Layout;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

class Sidenav extends Component
{
    public Collection $bimbles;
    public array $months;

    public function render()
    {
        $this->bimbles = auth()->user()->bimbles;

        for($i=0; $i < 10; $i++) {
            $month = Carbon::now()->subMonths($i);

            $bimbles = $this->bimbles->filter(function($bimble) use ($month) {
                return $bimble->started_at->month === $month->month;
            });

            $days = [];

            $bimbles->each(function($bimble) use (&$days) {
                $days[$bimble->started_at->day] = $bimble->started_at;
            });

            if ($bimbles->isEmpty()) {
                continue;
            }

            $this->months[] = [
                'text' => $month->monthName,
                'number' => $month->month,
                'days' => $days,
            ];


        }

        return view('livewire.layout.sidenav');
    }
}
