<ul>
    @foreach($filterItem->children as $item)
        <li wire:key="{{$item->uid}}">
            <a wire:click="toggleItem()">
                <input type="checkbox" x-show="$wire.item.active" checked/>
                <span>{{ $item->name }}</span>
                <span>{{ $item->active? 'active': 'not' }}</span>
                <livewire:components.tree-filter :filterItem="$item" :key="$item->uid"/>
            </a>
        </li>
    @endforeach
</ul>
