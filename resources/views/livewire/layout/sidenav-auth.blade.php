@php use Carbon\Carbon; @endphp
<nav class="sidenav">

    <x-sidenav-header></x-sidenav-header>

    <ul>
        <template
            x-data="{ navTree: $wire.entangle('navTree').live }"
            x-for="year in navTree"
            wire:key="year.id"
        >
            <li>
                <a
                    x-on:click="year.active = !year.active"
                    x-text="year.id">
                </a>
                <ul
                    x-show="year.active"
                >
                    <template
                        x-data="{ navTree: $wire.entangle('navTree').live }"
                        x-for="month in year.months"
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
                                            x-text="day.text">
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
                                                    >
                                                        <input type="checkbox" x-show="bimble.active" checked/>
                                                        <span x-text="bimble.text"></span>
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
            </li>
        </template>
    </ul>

    <livewire:components.logout-button/>
</nav>
