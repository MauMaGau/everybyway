@php use Carbon\Carbon; @endphp
<nav class="sidenav">

    <x-sidenav-header></x-sidenav-header>

    <ul >
        <template
            x-for="month in $wire.months"
            wire:key="month.id"
            x-data="{ showing: $wire.entangle('showing') }">
            <li>
                <a
                    wire:click="filter(month.number)"
                    x-on:click="showing[month.id].active = !showing[month.id].active"
                    x-text="month.text">
                </a>
                <ul
                    x-show="showing[month.id].active"
                    x-transition>
                    <template
                        x-for="day in month.days"
                        wire:key="day.id">
                        <li>
                            <a
                                wire:click="filter(month.number, day.date)"
                                x-on:click="showing[month.id].days[day.id].active = !showing[month.id].days[day.id].active"
                                x-text="day.date_display">
                            </a>
                            <ul
                                x-show="showing[month.id].days[day.id].active">
                                <template
                                    x-for="bimble in day.bimbles"
                                    wire:key="bimble.id">
                                    <li>
                                        <a
                                            wire:click="filter(month.number, day.date, bimble.started_at)"
                                            x-on:click="showing[month.id].days[day.id].bimbles[bimble.id].active = !showing[month.id].days[day.id].bimbles[bimble.id].active"
                                            x-text="bimble.started_at_display + ' - ' + bimble.ended_at_display">
                                        </a>
                                    </li>
                                </template>
                            </ul>
                        </li>
                    </template>
                </ul>
            </li>
        </template>
    </ul>

    <livewire:components.logout-button/>
</nav>
