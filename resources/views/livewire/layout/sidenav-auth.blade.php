@php use Carbon\Carbon; @endphp
<nav class="sidenav">

    <x-sidenav-header></x-sidenav-header>

    <ul >
        <template
            x-data="{ months: $wire.entangle('months').live }"
            x-for="month in months"
            wire:key="month.id"
        >
            <li>
                <a
                    x-on:click="month.active = !month.active"
                    x-text="month.text">
                </a>
                <ul
                    x-show="month.active"
                    x-transition>
                    <template
                        x-for="day in month.days"
                        wire:key="day.id">
                        <li>
                            <a
                                x-on:click="day.active = !day.active"
                                x-text="day.date_display">
                            </a>
                            <ul
                                x-show="day.active"
                            >
                                <template
                                    x-for="bimble in day.bimbles"
                                    wire:key="bimble.id">
                                    <li>
                                        <a
                                            x-on:click="bimble.active = !bimble.active"
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
