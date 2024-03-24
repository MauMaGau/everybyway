<div>
    <ul>
        <livewire:components.tree-filter :filterItem="$rootFilterItem"/>
{{--        <template--}}
{{--            x-for="$wire.treeFilter.topLevelItems() as item"--}}
{{--            wire:key="item.name"--}}
{{--        >--}}
{{--            <livewire:components.tree-filter :filterItem="item"/>--}}
{{--        </template>--}}
{{--        <template--}}
{{--            x-data="{ navTree: $wire.entangle('navTree').live }"--}}
{{--            x-for="year in navTree"--}}
{{--            wire:key="year.id"--}}
{{--        >--}}
{{--            <li>--}}
{{--                <a--}}
{{--                    wire:click="itemToggle(year)"--}}
{{--                    x-text="year.id">--}}
{{--                </a>--}}
{{--                <ul--}}
{{--                    x-show="year.active"--}}
{{--                >--}}
{{--                    <template--}}
{{--                        x-data="{ navTree: $wire.entangle('navTree').live }"--}}
{{--                        x-for="month in year.months"--}}
{{--                        wire:key="month.id"--}}
{{--                    >--}}
{{--                        <li>--}}
{{--                            <a wire:click="itemToggle(month)">--}}
{{--                                <span x-text="month.text"></span>--}}
{{--                                <small x-text="month.hasActiveDays"></small>--}}
{{--                                <small x-text="month.hasActiveBimbles"></small>--}}
{{--                                <small x-text="month.active"></small>--}}
{{--                            </a>--}}
{{--                            <ul--}}
{{--                                x-show="month.active"--}}
{{--                                x-transition>--}}
{{--                                <template--}}
{{--                                    x-for="day in month.days"--}}
{{--                                    wire:key="day.id">--}}
{{--                                    <li>--}}
{{--                                        <a wire:click="itemToggle(day)">--}}
{{--                                            <input type="checkbox" x-show="day.active && !day.hasActiveBimbles" checked/>--}}
{{--                                            <span x-text="day.text"></span>--}}
{{--                                            <small x-text="day.hasActiveBimbles"></small>--}}
{{--                                            <small x-text="day.active"></small>--}}
{{--                                        </a>--}}
{{--                                        <ul--}}
{{--                                            x-show="day.active"--}}
{{--                                        >--}}
{{--                                            <template--}}
{{--                                                x-for="bimble in day.bimbles"--}}
{{--                                                wire:key="bimble.id">--}}
{{--                                                <li>--}}
{{--                                                    <a wire:click="itemToggle(day)">--}}
{{--                                                        <input type="checkbox" x-show="bimble.active" checked/>--}}
{{--                                                        <span x-text="bimble.text"></span>--}}
{{--                                                    </a>--}}
{{--                                                </li>--}}
{{--                                            </template>--}}
{{--                                        </ul>--}}
{{--                                    </li>--}}
{{--                                </template>--}}
{{--                            </ul>--}}
{{--                        </li>--}}
{{--                    </template>--}}
{{--                </ul>--}}
{{--            </li>--}}
{{--        </template>--}}
    </ul>
</div>
