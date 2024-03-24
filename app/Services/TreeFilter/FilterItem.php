<?php

namespace App\Services\TreeFilter;

use Illuminate\Support\Collection;
use Livewire\Wireable;

class FilterItem implements Wireable
{
    public string      $name;
    public ?Collection $children;
    public ?FilterItem $parent;
    public bool        $active;
    public ?Collection $items;
    public string $uid;

    public function __construct(?string $name = 'unnammed item', ?Collection $children = null, ?FilterItem $parent = null, ?Collection $items = null, ?bool $active = false)
    {
        $this->children = $children?: collect();
        $this->parent = $parent?: null;
        $this->items = $items?: collect();
        $this->name = $name;
        $this->active = $active;
        $this->uid = uniqid();
    }

    public function setParent(FilterItem $parent)
    {
        $this->parent = $parent;
    }

    public function addChild(FilterItem $filterItem)
    {
        if (! $this->children->where('name', $filterItem->name)->count()) {
            $this->children->push($filterItem);
        }
    }

    public function addChildren(Collection $children)
    {
        $this->children = $this->children->merge($children);
    }

    public function toLivewire(): array
    {
        return [
            'name' => $this->name,
            'children' => $this->children,
//            'parent' => $this->parent,
            'items' => $this->items,
            'active' => $this->active,
        ];
    }

    public static function fromLivewire($value): FilterItem
    {
        $name = $value['name'];
        $children = $value['children'];
//        $parent = $value['parent'];
        $items = $value['items'];
        $active = $value['active'];

        return new static($name, $children, null,$items, $active);
    }
}
