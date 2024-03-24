<?php

namespace App\Services\TreeFilter;

use Illuminate\Support\Collection;
use Livewire\Wireable;

class TreeFilter implements Wireable
{
    public Collection $items;

    public function __construct(?Collection $items = null)
    {
        $this->items = $items?: collect();
    }

    public function createOrUpdateItem(string $name, Collection $items, ?FilterItem $parent = null, ?Collection $children = null): FilterItem
    {
        $filterItem = $this->findByName($name);

        if (! $filterItem) {
            $filterItem = $this->createItem($name);
        }

        $filterItem->items = $filterItem->items->merge(collect($items));

        if ($children) {
            $filterItem->addChildren($children);
        }

        if ($parent) {
            $filterItem->setParent($parent);
            $parent->addChild($filterItem);
        }

        return $filterItem;
    }

    public function createItem(string $name): FilterItem
    {
        $filterItem = new FilterItem($name);
        $this->items->push($filterItem);
        return $filterItem;
    }

    public function topLevelItems(): Collection
    {
        return $this->items->filter(function (FilterItem $filterItem) {
            return $filterItem->parent === null;
        });
    }

    public function findByName(string $name): ?FilterItem
    {
        return $this->items->first(function (FilterItem $filterItem) use ($name) {
            return $filterItem->name == $name;
        });
    }

    public function findByUid(string $uid): ?FilterItem
    {
        return $this->items->first(function (FilterItem $filterItem) use ($uid) {
            return $filterItem->uid == $uid;
        });
    }

    public function toLivewire(): array
    {
        return [
            'items' => $this->items,
        ];
    }

    public static function fromLivewire($value): TreeFilter
    {
        $items = $value['items'];

        return new static($items);
    }
}
